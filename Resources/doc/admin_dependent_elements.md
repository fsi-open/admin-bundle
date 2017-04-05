# How to create a dependent CRUD element

## 1. Create the parent CRUD (or list) element

Please refer to [Doctrine CRUD documentation](admin_element_crud.md) for basic explanations.

## 2. Configure additional datagrid action

Datagrid configuration for parent CRUD element not differ too much from regular CRUD element.
The only difference is the action that should lead to dependent CRUD (or any other) element 

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/categories.yml

columns:
  actions:
    type: action
    options:
      label: Actions
      field_mapping: [ id ]
      actions:
        news:
          route_name: fsi_admin_crud_list
          additional_parameters:
            element: category_news # ID of dependent admin element
          parameters_field_mapping:
            parent: id # ID of object for which action was clicked is passed as "parent" query parameter to dependent element 
          redirect_uri: false # do not generate "redirect_uri" query parameter
```

**Please note** that `redirect_uri` action's option should be disabled because otherwise this query
parameter would interfere with dependent CRUD's operation (i.e. misleading redirects after successful
batch action)

[DataGrid action column reference](https://github.com/fsi-open/datagrid-bundle/blob/master/Resources/docs/columns/action.md)

## 3. Create dependent CRUD (or any other) element

Please refer to [Doctrine CRUD documentation](admin_element_crud.md) for basic instructions.
Here are some differences when creating dependent CRUD element:

- Inherit from `FSi\Bundle\AdminBundle\Doctrine\Admin\DependentCRUDElement` instead of
  `FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement`
- Implement `getParentId()` method which should return ID of parent admin element.
  This ID is mainly used to highlight the right link in main menu, because usually
  your dependent admin element will not be linked from it directly
- Use `getParentObject()` method to access object instance for which dependent admin
  element was invoked. It should be helpful to constrain datasource results to objects
  associated with the parent object (i.e. only news objects assigned to category),
  and/or construct new instance of child object during form creation (i.e. create news
  object already assigned to specific category). The parent object can also be used in
  dependent element's view template i.e. to display its name and/or some url in
  breadcrumbs.

## 4. CRUD is not the only thing you usually need

As mentioned before dependent admin elements are not constrained only to CRUDs. Here's
the complete list of dependent admin elements abstract classes:

<table>
    <thead>
        <tr>
            <th>Generic</th>
            <th>Doctrine</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>FSi\Bundle\AdminBundle\Admin\CRUD\DependentBatchElement</td>
            <td>FSi\Bundle\AdminBundle\Doctrine\Admin\DependentBatchElement</td>
        </tr>
        <tr>
            <td>FSi\Bundle\AdminBundle\Admin\CRUD\DependentCRUDElement</td>
            <td>FSi\Bundle\AdminBundle\Doctrine\Admin\DependentCRUDElement</td>
        </tr>
        <tr>
            <td>FSi\Bundle\AdminBundle\Admin\CRUD\DependentDeleteElement</td>
            <td>FSi\Bundle\AdminBundle\Doctrine\Admin\DependentDeleteElement</td>
        </tr>
        <tr>
            <td>FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement</td>
            <td>FSi\Bundle\AdminBundle\Doctrine\Admin\DependentFormElement</td>
        </tr>
        <tr>
            <td>FSi\Bundle\AdminBundle\Admin\CRUD\DependentListElement</td>
            <td>FSi\Bundle\AdminBundle\Doctrine\Admin\DependentListElement</td>
        </tr>
        <tr>
            <td>FSi\Bundle\AdminBundle\Admin\Display\DependentDisplayElement</td>
            <td>FSi\Bundle\AdminBundle\Doctrine\Admin\DependentDisplayElement</td>
        </tr>
    </tbody>
</table>

[Back to index](index.md)
