## Dependencies ##
Vagrant: https://www.vagrantup.com/docs/installation/

## Create VM: ##
`vagrant up`

## Login to VM: ##
`vagrant ssh`

## Destroy VM: ##
`vagrant destroy`

## Get IP Address: ##
`ifconfig`

## Get Process ID of Multicast Process: ##
`ps cax | grep multi`

## Get Console output from Multicast Process: ##
`sudo strace -p1234 -s9999 -e write` Where 1234 is the process ID from previous command

## iNotes URLS: ##
Control Panel: `/ControlPanel.php`
iNotes Authoring: `/CreateContent`