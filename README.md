# FSi Admin Bundle

FSi Admin Bundle is a complete solution that provides mechanisms to generate an admin panel for any Symfony2 (and 3) based application.

> **Important** - the bundle is not integrated with Symfony's security component. By default the path /admin is not protected
> and you need to secure it on your own. It's recommended to use [FSiAdminSecurityBundle](https://github.com/fsi-open/admin-security-bundle)

Build Status:
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.svg?branch=master)](https://travis-ci.org/fsi-open/admin-bundle) - Master
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.svg?branch=3.1)](https://travis-ci.org/fsi-open/admin-bundle) - 3.1
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.svg?branch=3.0)](https://travis-ci.org/fsi-open/admin-bundle) - 3.0
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.svg?branch=2.1)](https://travis-ci.org/fsi-open/admin-bundle) - 2.1
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.svg?branch=2.0)](https://travis-ci.org/fsi-open/admin-bundle) - 2.0
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.svg?branch=1.0)](https://travis-ci.org/fsi-open/admin-bundle) - 1.0

[![Latest Stable Version](https://poser.pugx.org/fsi/admin-bundle/v/stable.png)](https://packagist.org/packages/fsi/admin-bundle)

Code quality:
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/82a9e680-cff7-460b-973c-67a2fc7adac0/mini.png)](https://insight.sensiolabs.com/projects/82a9e680-cff7-460b-973c-67a2fc7adac0)

Documentation:

- [For master](Resources/doc/index.md)
- [For version 3.1](https://github.com/fsi-open/admin-bundle/blob/3.1/README.md)
- [For version 2.0](https://github.com/fsi-open/admin-bundle/blob/2.0/README.md)
- [For version 1.0](https://github.com/fsi-open/admin-bundle/blob/1.0/README.md)

# Features

- Column types: text, number, money, date, datetime, boolean, action
- Extension system that allows you to create your own custom column types
- Sorting, ordering, filtering, pagination, batch actions, custom actions
- Doctrine ORM\ODM support, with possibility to create data sets from query builder
- Manually positioning entities on lists
- Inline editing at list level
- Fully integrated with Symfony's Form component
- Fully integrated with FSi's ResourceRepositoryBundle
- Fully translatable
- Every single part can be easily overwritten
- Customizable through a powerful event system
- [Bootstrap 3](http://getbootstrap.com/) design (easy to change and adapt to your needs)
- ... and many, many more ;)

# Architecture

FSi AdminBundle, unlike other open source tools for generating admin panels, is built on top of
components designed to do only one thing at once. That's why our AdminBundle is flexible and fully extendable.

Below is a full list of components, tools and bundles used during the creation of FSiAdminBundle:

- [Symfony form component](https://github.com/symfony/form)
- [FSi Open datagrid](https://github.com/fsi-open/datagrid-bundle)
- [FSi Open datasource](https://github.com/fsi-open/datasource-bundle)
- [FSi Open resource repository](https://github.com/fsi-open/resource-repository-bundle)
- [FSi Open dataindexer](https://github.com/fsi-open/data-indexer)
- [KnpLabs menu](https://github.com/KnpLabs/KnpMenuBundle)
- [PHPSpec](https://github.com/phpspec)
- [Behat](https://github.com/behat)
- [Behat - Page Object Extension](https://github.com/sensiolabs/BehatPageObjectExtension)

# Tests

Because a few tests require Javascript in order to pass, it's recommended to use the Vagrant virtual machine.
All you need to do is go to the `vagrant` folder in the project and start the pre-configured VM there:

```
$ cd vagrant
$ vagrant up
```

Then log into the VM and run Behat/PHPSpec suites from the project's root directory:

```
$ cd /var/www/admin-bundle/
$ bin/behat
$ bin/phpspec
```
