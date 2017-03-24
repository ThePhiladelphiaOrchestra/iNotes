# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.box_version = "20160319.0.0"
  config.vm.provision :shell, path: "bootstrap.sh"
  config.vm.network "private_network", ip: "172.30.1.10"
end
