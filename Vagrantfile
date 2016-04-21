# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.provision :shell do |shell|
    shell.path = "./ansible/bootstrap.sh"
  end
  
  config.vm.provision :shell do |shell|
    shell.inline = "cd /vagrant/ansible && ansible-playbook dev.yml"
  end  

  config.vm.network :forwarded_port, guest: 80, host: 8285
  
  # establish a private network so that we can use an NFS shared
  # folder (the default VirtualBox shared folders are slow and
  # materially impact PHP performance).
  config.vm.network :private_network, ip: "10.0.10.2"

  config.vm.synced_folder ".", "/vagrant", type: 'nfs'

  # Drush and composer are both memory hogs that will often simply die
  # on the default sized VM.
  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
    v.cpus = 2
  end

end
