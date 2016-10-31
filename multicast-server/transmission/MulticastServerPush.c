/**
 * Simple example of transmission and reception via multicast
 * http://atastypixel.com/blog/the-making-of-talkie-multi-interface-broadcasting-and-multicast/
 *
 *  Compile with:
 *      cc -o multicast_sample multicast_sample.c
 *
 *  Usage:
 *    To transmit:
 *      ./multicast_sample "Message to send"
 *
 *    To receive, call with no arguments:
 *      ./multicast_sample
 *
 * Michael Tyson, A Tasty Pixel <michael@atastypixel.com>
 * http://atastypixel.com
 */

#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <net/if.h>
#include <ifaddrs.h>
#include <string.h>
#include <stdio.h>
#include <errno.h>
#include <time.h>
#include "include/mysql.h"

#define kMulticastAddress "239.255.255.251"
#define kPortNumber 12345
#define kBufferSize 250
#define kMaxSockets 16

/*!
 *   Main entry point
 */
int main(int argc, char** argv) {
  //read from mysql and transmit ever 1/3 seconds
  if ( argc < 2 ) {
    
    MYSQL *conn;
    MYSQL_RES *res;
    MYSQL_ROW row;
    char *server = "PUT.SERVER.URL.OR.IP_ADDR.HERE";
    char *user = "mysql_username";
    char *password = "mysql_password";
    char *database = "database_name";

    conn = mysql_init(NULL);
    
    /* Connect to database */
    if (!mysql_real_connect(conn, server,user, password, database, 0, NULL, 0)) {
        fprintf(stderr, "%s\n", mysql_error(conn));
        return 0;
    }

      int sock_fds[kMaxSockets];
      
      // Obtain list of all network interfaces
      struct ifaddrs *addrs;
      if ( getifaddrs(&addrs) < 0 ) {
          // Error occurred
          return 0;
      }
      
      // Loop through interfaces, selecting those AF_INET devices that support multicast, but aren't loopback or point-to-point
      const struct ifaddrs *cursor = addrs;
      struct sockaddr_in addr;
      int number_sockets = 0;
      
      while ( cursor != NULL && number_sockets < kMaxSockets ) {
          if ( cursor->ifa_addr->sa_family == AF_INET 
              && !(cursor->ifa_flags & IFF_LOOPBACK) 
              && !(cursor->ifa_flags & IFF_POINTOPOINT) 
              &&  (cursor->ifa_flags & IFF_MULTICAST) ) {
              
              // Create socket
              //printf("Create socket\n");
              sock_fds[number_sockets] = socket(AF_INET, SOCK_DGRAM, 0);
              if ( sock_fds[number_sockets] == -1 ) {
                  // Error occurred
                  return 0;
              }
              
              // Make the socket transmit through this interface
              //printf(" Make the socket transmit through current interface: %d\n",sock_fds[number_sockets]);
              if ( setsockopt(sock_fds[number_sockets], IPPROTO_IP, IP_MULTICAST_IF, &((struct sockaddr_in *)cursor->ifa_addr)->sin_addr, sizeof(struct in_addr)) != 0  ) {
                  // Error occurred
                  return 0;
              }
              
              // We're not interested in receiving our own messages, so we can disable loopback (don't rely solely on this - in some cases you can still receive your own messages)
              
              //printf("Disable loopback\n");
              u_char loop = 0;
              if ( setsockopt(sock_fds[number_sockets], IPPROTO_IP, IP_MULTICAST_LOOP, &loop, sizeof(loop)) != 0 ) {
                  // Error occurred
                  return 0;
              }
              
              number_sockets++;
          }
          cursor = cursor->ifa_next;
      }
      
      // Initialise multicast address
      //printf("Initialise multicast address\n");
      memset(&addr, 0, sizeof(addr));
      addr.sin_family = AF_INET;
      addr.sin_addr.s_addr = inet_addr(kMulticastAddress);
      addr.sin_port = htons(kPortNumber);  
      
    int count = 0;
    while(1)
    {
        /* send SQL query */
        if (mysql_query(conn, "SELECT currentMeasure FROM currentMeasure")) {
            fprintf(stderr, "%s\n", mysql_error(conn));
            return 0;
        }
             
        res = mysql_use_result(conn);
        
        char s[250];
        /* output currentMeasure */
        while ((row = mysql_fetch_row(res)) != NULL)
        {  
            //printf("%s\n", row[0]);
            int num = sprintf(s,"%s", row[0]);
        }
           
        
        /* send SQL query */
        if (mysql_query(conn, "SELECT currentPiece FROM currentMeasure")) {
            fprintf(stderr, "%s\n", mysql_error(conn));
            return 0;
        }
             
        res = mysql_use_result(conn);
         
        char status[70];
        /* output currentPiece */
        while ((row = mysql_fetch_row(res)) != NULL)
        {
            //printf("%s\n", row[0]);
            int num  = sprintf(status, "%s; %s ", row[0],s);
        }
        
        int length = sizeof(status);

        /* send SQL query */
        char pushMessage[250];
        if(count%10 == 0)
        {
          
          for (int i=0; i<250; i++)
          {
            pushMessage[i] = ' ';
          } 

          if (mysql_query(conn, "SELECT currentNotification FROM currentMeasure")) {
            fprintf(stderr, "%s\n", mysql_error(conn));
            return 0;
          }
                     
          res = mysql_use_result(conn);
           
          
          /* output currentPiece */
          while ((row = mysql_fetch_row(res)) != NULL)
          {
              //printf("%s\n", row[0]);
              int num  = sprintf(pushMessage, "%s",row[0]);
          }
        }
        

        int pushLength = sizeof(pushMessage);
        
        
        
        
        int i;
        int succ = 1;
        
        char sendstuffbuff[250];
        for ( i=0; i<250; i++)
        {
          sendstuffbuff[i] = ' ';
        } 
        
        int tempjawn = sprintf(sendstuffbuff,"%s : %d",status,count);
        
        for ( i=0; i<number_sockets; i++ ) {
            
            int num;

            if(count%10 == 0)
            {
              num =  sendto(sock_fds[i], pushMessage, pushLength, 0, (struct sockaddr*)&addr, sizeof(addr));
              //num =  sendto(sock_fds[i], sendstuffbuff, length, 0, (struct sockaddr*)&addr, sizeof(addr));
            }
            else
            {
              num =  sendto(sock_fds[i], sendstuffbuff, length, 0, (struct sockaddr*)&addr, sizeof(addr));
            }
            // printf("Sent through %d STATUS: %d\t", i, num);
            if ( num < 0 ) {
                succ = 0;
            }
            else
            {
                // printf("Sent through: %d\n", i);
            }
        }
        
        
        
        if(count%10 == 0)
            {
              printf("%s \tCOUNT:%d SUCCESS:%d\n", pushMessage, count, succ);
            }
            else
            {
              printf("%s \tCOUNT:%d SUCCESS:%d\n", status, count, succ);
            }
        
        
        
        usleep(100000);
        count++;
        // free(pushMessage);
        // free(status);
        // free(s);
        // free(sendstuffbuff);
    }
    
     /* close connection */
    mysql_free_result(res);
    mysql_close(conn);
    return 0;
    

    // Argument provided: Transmit this
    printf("Starting Transmission\n");
    if ( transmit(argv[1], strlen(argv[1])) ) {
      printf("\"%s\" transmitted.\n", argv[1]);
    } else {
      printf("Error occurred: %s\n", strerror(errno));
      return 1;
    }
    return 0;
  }

}

/*!
 *  Transmit data
 *
 *  @param data
 *    Data to send
 *  @param length
 *    Length of data, in bytes
 *  @result 0 on failure, 1 on success
 */
int transmit(char * data, int length) {

  int sock_fds[kMaxSockets];
  
  // Obtain list of all network interfaces
  struct ifaddrs *addrs;
  if ( getifaddrs(&addrs) < 0 ) {
    // Error occurred
    return 0;
  }
  
  // Loop through interfaces, selecting those AF_INET devices that support multicast, but aren't loopback or point-to-point
  const struct ifaddrs *cursor = addrs;
  struct sockaddr_in addr;
  int number_sockets = 0;
  
  while ( cursor != NULL && number_sockets < kMaxSockets ) {
    if ( cursor->ifa_addr->sa_family == AF_INET 
            && !(cursor->ifa_flags & IFF_LOOPBACK) 
            && !(cursor->ifa_flags & IFF_POINTOPOINT) 
            &&  (cursor->ifa_flags & IFF_MULTICAST) ) {

      // Create socket
       //printf("Create socket\n");
      sock_fds[number_sockets] = socket(AF_INET, SOCK_DGRAM, 0);
      if ( sock_fds[number_sockets] == -1 ) {
        // Error occurred
        return 0;
      }
      
      // Make the socket transmit through this interface
       //printf(" Make the socket transmit through current interface: %d\n",sock_fds[number_sockets]);
      if ( setsockopt(sock_fds[number_sockets], IPPROTO_IP, IP_MULTICAST_IF, &((struct sockaddr_in *)cursor->ifa_addr)->sin_addr, sizeof(struct in_addr)) != 0  ) {
        // Error occurred
        return 0;
      }
      
      // We're not interested in receiving our own messages, so we can disable loopback (don't rely solely on this - in some cases you can still receive your own messages)
      
      //printf("Disable loopback\n");
      u_char loop = 0;
      if ( setsockopt(sock_fds[number_sockets], IPPROTO_IP, IP_MULTICAST_LOOP, &loop, sizeof(loop)) != 0 ) {
        //Error occurred
        return 0;
      }
      
      number_sockets++;
    }
    cursor = cursor->ifa_next;
  }
  
  // Initialise multicast address
  //printf("Initialise multicast address\n");
  memset(&addr, 0, sizeof(addr));
  addr.sin_family = AF_INET;
  addr.sin_addr.s_addr = inet_addr(kMulticastAddress);
  addr.sin_port = htons(kPortNumber);
  
  // Send through each interface
   //printf("Send through each interface\n");
  
  //int tempjawn = sprintf(data,"%s   :%d")
    
  int i;
  for ( i=0; i<number_sockets; i++ ) {
      int num =  sendto(sock_fds[i], data, length, 0, (struct sockaddr*)&addr, sizeof(addr));
     // printf("Sent through %d STATUS: %d\t", i, num);
    if ( num < 0 ) {
      // Error occurred
      return 0;
    }
    else
    {
       // printf("Sent through: %d\n", i);
    }
  }
  
  return 1;
}

/*!
 *  Receive loop
 *
 *  @description
 *    Loops forever, waiting for data, and printing whatever comes in.
 *  @result 0 on failure
 */
int receive() {
  
  // Create socket
  int sock_fd = socket(AF_INET, SOCK_DGRAM, 0);
  if ( sock_fd == -1 ) {
    // Error occurred
    return 0;
  }
  
  // Create address from which we want to receive, and bind it
  struct sockaddr_in addr;
  memset(&addr, 0, sizeof(addr));
  addr.sin_family = AF_INET;
  addr.sin_addr.s_addr = INADDR_ANY;
  addr.sin_port = htons(kPortNumber);
  if ( bind(sock_fd, (struct sockaddr*)&addr, sizeof(addr)) < 0 ) {
    // Error occurred
    return 0;
  }
  
  // Obtain list of all network interfaces
  struct ifaddrs *addrs;
  if ( getifaddrs(&addrs) < 0 ) {
    // Error occurred
    return 0;
  }
  
  // Loop through interfaces, selecting those AF_INET devices that support multicast, but aren't loopback or point-to-point
  const struct ifaddrs *cursor = addrs;
  while ( cursor != NULL ) {
    if ( cursor->ifa_addr->sa_family == AF_INET 
             && !(cursor->ifa_flags & IFF_LOOPBACK) 
             && !(cursor->ifa_flags & IFF_POINTOPOINT) 
             &&  (cursor->ifa_flags & IFF_MULTICAST) ) {
        
      // Prepare multicast group join request
      struct ip_mreq multicast_req;
      memset(&multicast_req, 0, sizeof(multicast_req));
      multicast_req.imr_multiaddr.s_addr = inet_addr(kMulticastAddress);
      multicast_req.imr_interface = ((struct sockaddr_in *)cursor->ifa_addr)->sin_addr;
      
      // Workaround for some odd join behaviour: It's perfectly legal to join the same group on more than one interface,
      // and up to 20 memberships may be added to the same socket (see ip(4)), but for some reason, OS X spews 
      // 'Address already in use' errors when we actually attempt it.  As a workaround, we can 'drop' the membership
      // first, which would normally have no effect, as we have not yet joined on this interface.  However, it enables
      // us to perform the subsequent join, without dropping prior memberships.
      setsockopt(sock_fd, IPPROTO_IP, IP_DROP_MEMBERSHIP, &multicast_req, sizeof(multicast_req));
      
      // Join multicast group on this interface
      if ( setsockopt(sock_fd, IPPROTO_IP, IP_ADD_MEMBERSHIP, &multicast_req, sizeof(multicast_req)) < 0 ) {
        // Error occurred
        return 0;
      }
    }
    cursor = cursor->ifa_next;
  }
  
  char buffer[kBufferSize];
  socklen_t addr_len = sizeof(addr);
  
  while ( 1 ) {
    
    // Receive a message, waiting if there's nothing there yet
    int bytes_received = recvfrom(sock_fd, buffer, kBufferSize, 0, (struct sockaddr*)&addr, &addr_len);
    if ( bytes_received < 0 ) {
       // Error occurred
       return 0;
    }
    
    // Now we have bytes_received bytes of data in buffer. Print it!
    fwrite(buffer, sizeof(char), bytes_received, stdout);
    printf("\n");
  }  
}

