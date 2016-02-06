#!/bin/bash

# This is where you can choose to switch to a newer version of
# ansible. We're installing directly from source because it tends to
# have a lot of features & bugfixes not available in debian's package.
ANSIBLE_VERSION=375eb501b3b1edf7fd91807374edfcd60ca736b8

which ansible > /dev/null
if [ "$?" = "0" ];then
   exit 0
fi

set -e

BUILD_PATH=$HOME/src

apt-get update
apt-get install --no-install-recommends -y python-paramiko python-yaml python-jinja2 python-httplib2 python-setuptools python-six sshpass cdbs debhelper dpkg-dev git-core reprepro python-support fakeroot asciidoc devscripts docbook-xml docbook-xsl xsltproc libxml2-utils build-essential

mkdir -p $BUILD_PATH
cd $BUILD_PATH
if [ ! -d "$BUILD_PATH/ansible" ]; then
   git clone git://github.com/ansible/ansible.git
fi
cd ./ansible
git checkout $ANSIBLE_VERSION
git submodule update --init --recursive
make deb

dpkg -i deb-build/**/ansible*.deb

