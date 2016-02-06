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

  config.vm.synced_folder ".", "/vagrant",
                          group: 'www-data',
                          mount_options: ["dmode=775,fmode=664"]
end
