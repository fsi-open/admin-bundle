# FSi Admin Bundle

FSi Admin Bundle is complete solution that provides mechanisms to generate admin panel for any Symfony2 based application.

> **Important** - admin bundle is not integrated with Symfony security component. By default route /admin is not protected
> and you need to secure it on your own. It's recommended to use [FSiAdminSecurityBundle](https://github.com/fsi-open/admin-security-bundle)

Build Status:  
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.png?branch=master)](https://travis-ci.org/fsi-open/admin-bundle) - Master  
[![Build Status](https://travis-ci.org/fsi-open/admin-bundle.png?branch=1.0)](https://travis-ci.org/fsi-open/admin-bundle) - 1.0  

[![Latest Stable Version](https://poser.pugx.org/fsi/admin-bundle/v/stable.png)](https://packagist.org/packages/fsi/admin-bundle)

Code quality:  
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/fsi-open/admin-bundle/badges/quality-score.png?s=fbe212a23fd11b49c05ac4e837d3de0a2cbadfd6)](https://scrutinizer-ci.com/g/fsi-open/admin-bundle/)  
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6cc967b7-6ed6-4dec-b0d7-efe03a813b32/mini.png)](https://insight.sensiolabs.com/projects/6cc967b7-6ed6-4dec-b0d7-efe03a813b32)  

Documentation:

- [Installation](Resources/doc/installation.md)
- [Admin Elements](Resources/doc/admin_element.md)
- [Embedding Element](Resources/doc/embedding_element.md)
- [Events](Resources/doc/events.md)
- [Bundle Configuration](Resources/doc/configuration.md)
- [Admin Panel Translation](Resources/doc/admin_panel_translation.md)
- [Behat - live documentation](features)

# Features

- Column types: text, number, money, date, datetime, boolean, action 
- Extension system that allows you to create own custom column types 
- Sorting, ordering, filters, pagination, batch actions, custom actions 
- Doctrine ORM\ODM support with possibility to create data sets from query builder 
- In place edit at list
- Full integration with Symfony2 Form component 
- Full integration with FSi ResourceRepositoryBundle
- Fully translatable
- Easy to overwrite in every single part 
- Customizable through powerful event system  
- [Bootstrap 3](http://getbootstrap.com/) design (easy to change)  
- ... and many many more ;) 

###[Demo](http://demo.fsi-open.com)

# Architecture 

FSi AdminBundle unlike to other available at github tools used to generate admin panels is build on top of
components designed to do only one thing at once. Thats why our AdminBundle is flexible and fully extendable.

List of components, tools and bundles used to create FSiAdminBundle

- [Symfony2 form component](https://github.com/symfony/form)
- [FSi Open datagrid](https://github.com/fsi-open/datagrid-bundle)
- [FSi Open datasource](https://github.com/fsi-open/datasource-bundle)
- [FSi Open resource repository](https://github.com/fsi-open/resource-repository-bundle)
- [FSi Open dataindexer](https://github.com/fsi-open/data-indexer)
- [KnpLabs menu](https://github.com/KnpLabs/KnpMenuBundle)
- [PHPSpec](https://github.com/phpspec)
- [Behat](https://github.com/behat)
- [Behat - Page Object Extension](https://github.com/sensiolabs/BehatPageObjectExtension)

# Tests

Because few tests require javascript its recommended to use vagrant virtual machine.
To configure virtual machine you need only go to vagrant folder in bundle

```
$ cd vagrant
$ vagrant up
```

Then login into VM and go to bundle folder and run Behat/PHPSpec.

```
$ cd /var/www/admin-bundle/
$ bin/behat
$ bin/phpspec
```
