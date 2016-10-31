#!/bin/bash          
file=$1
product_name=$2
if [ $# -lt 2 ] || [ $# -gt 2 ];
  then
    echo "Error, wrong number of arguments supplied."
    echo "Usage: sh makeServer.sh {file_to_build} {product_name}"
    exit 1
fi
cc -L/usr/lib/mysql -lmysqlclient -o $product_name $file_to_build
