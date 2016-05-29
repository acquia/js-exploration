# Drupal & Ember Integration Demo

## Repo Orientation

This repo contains:

`drupal`: a Drupal application, forked off the js-exploration branch.

`ember`: an Ember application, created by a standard `ember new` command.

`Vagrantfile`: this can bootstrap a fully working development server VM.

`ansible`': this is the Ansible-based config that Vagrant uses to build a new environment.


## Automatically Launch a New Drupal Instance

1. Install [Vagrant](https://www.vagrantup.com/) and [VirtualBox](https://www.virtualbox.org).
2. Ensure you are working with the 'ember' branch of the repository.
3. run `vagrant up` in this directory
4. View your new Drupal server at http://localhost:8285. Username `dev`, password `dev`.
