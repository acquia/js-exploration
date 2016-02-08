#!/bin/bash

which ansible > /dev/null
if [ "$?" = "0" ]; then
    exit 0
fi

set -e

apt-get install software-properties-common
apt-add-repository -y ppa:ansible/ansible
apt-get update
apt-get install -y ansible

