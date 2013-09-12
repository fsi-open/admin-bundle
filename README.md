# FSi Admin Bundle

FSi Admin Bundle is complete solution that provides mechanisms to generate admin panel for any Symfony2 based application.

> **Important** - admin bundle is not integrated with Symfony security component. By default route /admin is not protected
> and you need to add secure it on your own.

Documentation:

- [Installation](Resources/doc/installation.md)
- [Admin Elements](Resources/doc/admin_element.md)
- [Events](Resources/doc/events.md)
- [Bundle Configuration](Resources/doc/configuration.md)

# Features

- Column types: text, number, money, date, datetime, boolean, action 
- Extension system that allows you to create own custom column types 
- Sorting, ordering, filters, pagination, batch actions, custom actions 
- Doctrine ORM\ODM support with possibility to create data sets from query builder 
- In place edit at list
- Full integration with Symfony2 Form component 
- Full integration with FSi ResourceRepositoryBundle
- Fully translatable
- Easy to overwritte in every single part 
- Customizable through powerful event system  
- [Bootstrap 3](http://getbootstrap.com/) design (easy to change)  
- ... and many many more ;) 

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
