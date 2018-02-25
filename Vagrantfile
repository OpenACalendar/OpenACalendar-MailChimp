# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

	# It seems to need this here or "destroy" errors.
    config.vm.box = "ubuntu/xenial64"

	config.vm.define "app" do |normal|

        config.vm.box = "ubuntu/xenial64"
		config.ssh.username = "ubuntu"

		config.vm.synced_folder ".", "/vagrant",  :owner=> 'ubuntu', :group=>'users', :mount_options => ['dmode=777', 'fmode=777']

		config.vm.provider "virtualbox" do |vb|
			vb.gui = false

			vb.memory = "512"

			# https://github.com/boxcutter/ubuntu/issues/82#issuecomment-260902424
			vb.customize [
				"modifyvm", :id,
				"--cableconnected1", "on",
			]

		end

		config.vm.provision :shell, path: "vagrant/app/bootstrap.sh"

	end

end
