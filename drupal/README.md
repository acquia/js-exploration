# TL;DR

This (`js-exploration-base`) is the *base* branch, against which all PoCs should be developed.

# What?

This branch consists of:

1. Drupal 8 core (the `8.1.x` branch, i.e. the *next* minor version)
2. Patches against Drupal 8 core for bugs that are getting in the way of PoCs (for example: bugs in Drupal’s REST API).
3. An “install profile” called “JS exploration” that sets up default configuration and a custom theme.

The above means participants in this project can just rebase on top of this branch and automatically get the fixes to the bugs they report to us, so they can focus just on the JavaScript side of things. (In other words: let’s not require that all participants also become Drupal experts.)

# Practical

- Every participant works in their own branch, frequently rebasing on top of `js-exploration-base`.
- Every participant adds a Drupal module to implement their PoC to `/modules/<a module name>`. For an example, see the PoC that implements this using Drupal’s “AJAX Framework”: https://github.com/acquia/js-exploration/compare/js-exploration-base...ajax, and another one that uses Backbone: https://github.com/acquia/js-exploration/compare/js-exploration-base...backbone.
- Running into a Drupal problem? Create an issue in this GitHub project. We’ll fix it for you.

# Getting started

1. Environment: PHP 5.5.9 or newer, SQLite, MySQL, MariaDB or PostgreSQL. PHP 7 works also.
2. Web server (and probably a virtual host) must point to the directory that contains `index.php`.
3. Access that hostname via your browser. Welcome to Drupal’s installer. Choose the “JS exploration” installation profile.
