Puppet Xvfb Module
==================

Module for configuring xvfb.

Tested on Debian GNU/Linux 6.0 Squeeze and Ubuntu 10.4 LTS with
Puppet 2.6. Patches for other operating systems are welcome.


Installation
------------

Clone this repo to a xvfb directory under your Puppet modules directory:

    git clone git://github.com/uggedal/puppet-module-xvfb.git xvfb

If you don't have a Puppet Master you can create a manifest file
based on the notes below and run Puppet in stand-alone mode
providing the module directory you cloned this repo to:

    puppet apply --modulepath=modules test_xvfb.pp


Usage
-----

If you include the `xvfb` class xvfb will be installed along
with a init script:

    include xvfb
