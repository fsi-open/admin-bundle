# Manipulating tree structures on lists

This feature works with conjunction with [Tree - Nested Set behavior extension for Doctrine 2](https://github.com/doctrine-extension/DoctrineExtensions/blob/master/doc/tree.md).
The best method to use `DoctrineExtensions - Tree` in Symfony application is [StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle)

## 1. Install [StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle)

Add to `composer.json`:

```yaml
"require": {
    "stof/doctrine-extensions-bundle": "^2.4",
}
```

## 2. Register bundles

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
    );
}
```

## 3. Enable Tree Doctrine Extension

Add to `config/packages/stof_doctrine_extensions.yml`

```yml
stof_doctrine_extensions:
    orm:
        default:
            tree: true
```

## 4. Use in your datagrid definition

```yaml
columns:
  title:
    type: text
    options:
      label: "backend.categories.datagrid.title.label"
  actions:
    type: action
    options:
      display_order: 2
      label: "backend.categories.datagrid.actions.label"
      field_mapping: [ id ]
      actions:
        move_up:
          route_name: "fsi_admin_tree_node_move_up"
          additional_parameters: { element: "category" }
          parameters_field_mapping: { id: id }
          content: <span class="glyphicon glyphicon-arrow-up icon-white"></span>
          url_attr: { class: "btn btn-warning btn-sm" }
        move_down:
          route_name: "fsi_admin_tree_node_move_down"
          additional_parameters: { element: "category" }
          parameters_field_mapping: { id: id }
          content: <span class="glyphicon glyphicon-arrow-down icon-white"></span>
          url_attr: { class: "btn btn-warning btn-sm" }
```

## 5. Optionally listen to specific events

An event of respective class name is fired after moving an item up or down the tree:

- FSi\Bundle\AdminBundle\Event\MovedUpTreeEvent
- FSi\Bundle\AdminBundle\Event\MovedDownTreeEvent

Both contain the object that was being moved.
