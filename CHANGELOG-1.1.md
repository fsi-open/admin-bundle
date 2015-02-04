# CHANGELOG for 1.1

This document describes all the significant changes made between 1.0 and 1.1 branches
 
## Admin elements' changes

### Architectural changes

#### Splitting monolithic CRUD

The major change in admin-bundle's architecture is splitting one monolithic
[``FSi\Bundle\AdminBundle\Admin\CRUD\CRUDInterface``](Admin/CRUD/CRUDInterface.php) into a couple of smaller, more
specialized interfaces of admin elements:

- [``FSi\Bundle\AdminBundle\Admin\CRUD\ListElement``](Admin/CRUD/ListElement.php) - creates list of items using
  [fsi/datasource](https://github.com/fsi-open/datasource) and [fsi/datagrid](https://github.com/fsi-open/datagrid)
- [``FSi\Bundle\AdminBundle\Admin\CRUD\FormElement``](Admin/CRUD/FormElement.php) - creates form for a single item
- [``FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement``](Admin/CRUD/BatchElement.php) - performs some simple action on
  a single item

These interfaces have been combined again into
[``FSi\Bundle\AdminBundle\Admin\CRUD\CRUDElement``](Admin/CRUD/CRUDElement.php) which behaves exactly as the old one
[``FSi\Bundle\AdminBundle\Admin\CRUD\CRUDInterface``](Admin/CRUD/CRUDInterface.php) to ease the upgrade process.

#### New batch element

This [element](Admin/CRUD/BatchElement.php) was created to allow performing of repeatable operations on items selected
by user on the list. Please refer to the [full description](Resources/doc/admin_element_batch.md) of how to use such an
admin element. The first general use case of such an operation is deleting selected items, which is implemented as a
batch admin element. Other examples are activating/deactivating selected users, turning on/off visibility of selected
articles, moving selected articles to the archive, etc. New batch elements can be simply linked to the list element
using batch column options in datagrid.

#### New display element

Brand new [``FSi\Bundle\AdminBundle\Admin\Display\Element``](Admin/Display/Element.php) has been introduced and it
creates a specialized display object for a single item. This object is used to show selected properties of an item
(entity) in a standardized way.

#### Consequences

Thanks to the introducing of smaller admin elements it's now possible to build more complicated administration
interfaces with several different forms and/or displays for one type of item/entity. These different forms and/or
displays can be i.e. separately secured for different user roles.

Creation and editing of an item was previously handled by two different actions and their templates, but since one
admin list element can be linked to more than one form, such a separation is no longer necessary. The "add" button 
can simply link to different form element than "edit" button.

### Functional changes

#### Admin element registration

There is a new and easier way of registering admin elements by marking them with ``@Admin\Element`` annotation. If
you wish to set custom options for admin element using this method you have to do this in ``setDefaultOptions()``
through ``$resolver->setDefaults()``.

#### No confirmation during delete

Deleting of selected items no longer display intermediate action with confirmation form. Such a confirmation can
be added by attaching some JS to the "Ok" button which performs selected batch action.

#### Admin menu building

Method ``getName()`` was removed from all admin elements. Previously it was used only during building of the admin menu.
Now admin menu is built using additional configuration file as described [here](Resources/docs/menu.md).

### Options changes

#### Removed options

The following options were removed from [``FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD``](Admin/CRUD/AbstractCRUD)

- ``menu`` - removed from all admin elements in favour of new method of menu building method
- ``allow_edit`` - removed because there is no longer any specific "edit" action
- ``crud_list_title`` - removed in favour of ``header`` block in [list template](Resources/views/List/list.html.twig)
- ``crud_create_title`` - removed in favour of ``header`` block in [form template](Resources/views/Form/form.html.twig)
- ``crud_edit_title`` - removed in favour of ``header`` block in [form template](Resources/views/Form/form.html.twig)
- ``template_crud_delete`` - removed because delete action has no longer any intermediate confirmation action 

#### Added options

- ``template_list`` - template for the list action, its default value is taken from deprecated ``template_crud_list``
  option
- ``template_form`` - template for the form action, its default value is taken from deprecated ``template_crud_edit``
  option

#### Deprecated options

- ``template_crud_list`` - will be removed in favor of ``template_list``
- ``template_crud_edit`` - will be removed in favor of ``template_form``
- ``template_crud_create`` - will be removed in favor of ``template_form``

**Important!** - if you are using both ``template_crud_create`` and ``template_crud_edit``, please note that they have
to have the same value since there are no longer different contexts handling creation and update of an item.

### Templates changes

#### Removed templates

- ``Resources/views/CRUD/List/*.html.twig`` - these were the rarely used partials included by
  ``Resources/views/CRUD/list.html.twig``. Since there are gone, overwriting them in ``app/Resources`` has no longer
  any effect and ``Resources/views/CRUD/list.html.twig`` should be overwritten instead.
- ``Resources/views/CRUD/delete.html.twig`` - it is no longer needed since there is no longer any confirmation step
  while deleting selected items.

#### Changed templates

- ``Resources/views/CRUD/list.html.twig`` - it extends ``Resources/views/List/list.html.twig``. Since batch actions are
  added thought datagrid batch column's options, there are no longer ``batch_action`` and ``batch_form`` blocks to
  overwrite. There is a new block ``batch_actions`` containing the whole batch action selection form. 
- ``Resources/views/CRUD/edit.html.twig`` - it extends ``Resources/views/Form/form.html.twig``.
- ``Resources/views/CRUD/create.html.twig`` - it extends  ``Resources/views/CRUD/edit.html.twig`` and is preserved only
  for compatibility when someone overwrites it in their app.

## Contexts' changes

### Contexts handlers are more generic

All contexts and handlers classes which previously existed in ``FSi\Bundle\AdminBundle\Doctrine\Context`` namespace have
been removed in favour of new generic (non doctrine-dependent) contexts and handlers in
``FSi\Bundle\AdminBundle\Admin\CRUD\Context`` namespace. 

### Event names changes

Since contexts are rebuild from scratch the naming of events has been also changed.

<table>
  <tr>
    <th colspan="2">1.0.*</th>
    <th colspan="2">~1.1</th>
  </tr>
  <tr>
    <th>constant</th>
    <th>name</th>
    <th>constant</th>
    <th>name</th>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_CONTEXT_POST_CREATE</code></td><td><code>'admin.crud.list.context.post_create'</code></td>
    <td>-</td><td>-</td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND</code></td><td><code>'admin.crud.list.datasource.request.pre_bind'</code></td>
    <td><code>ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND</code></td><td><code>'admin.list.datasource.request.pre_bind'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND</code></td><td><code>'admin.crud.list.datasource.request.post_bind'</code></td>
    <td><code>ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND</code></td><td><code>'admin.list.datasource.request.post_bind'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND</code></td><td><code>'admin.crud.list.datagrid.data.pre_bind'</code></td>
    <td><code>ListEvents::LIST_DATAGRID_DATA_PRE_BIND</code></td><td><code>'admin.list.datagrid.data.pre_bind'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND</code></td><td><code>'admin.crud.list.datagrid.data.post_bind'</code></td>
    <td><code>ListEvents::LIST_DATAGRID_DATA_POST_BIND</code></td><td><code>'admin.list.datagrid.data.post_bind'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_PRE_BIND</code></td><td><code>'admin.crud.list.datagrid.request.pre_bind'</code></td>
    <td><code>ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND</code></td><td><code>'admin.list.datagrid.request.pre_bind'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_POST_BIND</code></td><td><code>'admin.crud.list.datagrid.request.post_bind'</code></td>
    <td><code>ListEvents::LIST_DATAGRID_REQUEST_POST_BIND</code></td><td><code>'admin.list.datagrid.request.post_bind'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_LIST_RESPONSE_PRE_RENDER</code></td><td><code>'admin.crud.list.response.pre_render'</code></td>
    <td><code>ListEvents::LIST_RESPONSE_PRE_RENDER</code></td><td><code>'admin.list.response.pre_render'</code></td>
  </tr>

  <tr>
    <td><code>CRUDEvents::CRUD_CREATE_CONTEXT_POST_CREATE</code><br /><code>CRUDEvents::CRUD_EDIT_CONTEXT_POST_CREATE</code></td>
    <td><code>'admin.crud.create.context.post_create'</code><br /><code>'admin.crud.edit.context.post_create'</code></td>
    <td>-</td><td>-</td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_CREATE_FORM_REQUEST_PRE_SUBMIT</code><br /><code>CRUDEvents::CRUD_EDIT_FORM_REQUEST_PRE_SUBMIT</code></td>
    <td><code>admin.crud.create.form.request.pre_submit'</code><br /><code>'admin.crud.edit.form.request.pre_submit'</code></td>
    <td><code>FormEvents::FORM_REQUEST_PRE_SUBMIT</code></td><td><code>'admin.form.request.pre_submit'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_CREATE_FORM_REQUEST_POST_SUBMIT</code><br /><code>CRUDEvents::CRUD_EDIT_FORM_REQUEST_POST_SUBMIT</code></td>
    <td><code>'admin.crud.create.form.request.post_submit'</code><br /><code>'admin.crud.edit.form.request.post_submit'</code></td>
    <td><code>FormEvents::FORM_REQUEST_POST_SUBMIT</code></td><td><code>'admin.form.request.post_submit'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_CREATE_ENTITY_PRE_SAVE</code><br /><code>CRUDEvents::CRUD_EDIT_ENTITY_PRE_SAVE</code></td>
    <td><code>'admin.crud.create.entity.pre_save'</code><br /><code>'admin.crud.edit.entity.pre_save'</code></td>
    <td><code>FormEvents::FORM_DATA_PRE_SAVE</code></td><td><code>'admin.form.data.pre_save'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_CREATE_ENTITY_POST_SAVE</code><br /><code>CRUDEvents::CRUD_EDIT_ENTITY_POST_SAVE</code></td>
    <td><code>'admin.crud.create.entity.post_save'</code><br /><code>'admin.crud.edit.entity.post_save'</code></td>
    <td><code>FormEvents::FORM_DATA_POST_SAVE</code></td><td><code>'admin.form.data.post_save'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_CREATE_RESPONSE_PRE_RENDER</code><br /><code>CRUDEvents::CRUD_EDIT_RESPONSE_PRE_RENDER</code></td>
    <td><code>'admin.crud.create.response.pre_render'</code><br /><code>'admin.crud.edit.response.pre_render'</code></td>
    <td><code>FormEvents::FORM_RESPONSE_PRE_RENDER</code></td><td><code>'admin.form.response.pre_render'</code></td>
  </tr>

  <tr>
    <td><code>CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE</code></td><td><code>'admin.crud.delete.context.post_create'</code></td>
    <td>-</td><td>-</td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_DELETE_FORM_PRE_SUBMIT</code></td><td><code>'admin.crud.delete.form.pre_submit'</code></td>
    <td><code>BatchEvents::BATCH_REQUEST_PRE_SUBMIT</code></td><td><code>'admin.batch.request.pre_submit'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_DELETE_FORM_POST_SUBMIT</code></td><td><code>'admin.crud.delete.form.post_submit'</code></td>
    <td><code>BatchEvents::BATCH_REQUEST_POST_SUBMIT</code></td><td><code>'admin.batch.request.post_submit'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE</code></td><td><code>'admin.crud.delete.entities.pre_delete'</code></td>
    <td><code>BatchEvents::BATCH_OBJECTS_PRE_APPLY</code></td><td><code>'admin.batch.objects.pre_apply'</code></td>
  </tr>
  <tr>
    <td><code>CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE</code></td><td><code>'admin.crud.delete.entities.post_delete'</code></td>
    <td><code>BatchEvents::BATCH_OBJECTS_POST_APPLY</code></td><td><code>'admin.batch.objects.post_apply'</code></td>
  </tr>
</table>

Additional notes:

- since there are no different events when creating and editing of an item you should distinguish these two situations
  by examining ``$event->getForm()->getData()`` in your event handler.
- since all batch events are common for all batch actions (i.e. not only delete), you should identify the action being
  performed by examining the class of ``$event->getElement()`` in your event handler.

## Bundle configuration changes

### Removed configuration options

The following bundle configuration options have been removed due to removal of their underlying functionality:

- ``fsi_admin.templates.crud_delete``
- ``fsi_admin.templates.edit_form_theme``
- ``fsi_admin.templates.create_form_theme``
- ``fsi_admin.templates.delete_form_theme``
- ``fsi_admin.templates.resource_form_theme``

### Routing changes

The following old routes have been marked deprecated and redirected to the new routes

- ``fsi_admin_crud_list`` -> ``fsi_admin_list``
- ``fsi_admin_crud_create`` -> ``fsi_admin_form``
- ``fsi_admin_crud_edit`` -> ``fsi_admin_form``
- ``fsi_admin_crud_delete`` -> ``fsi_admin_batch``

## Removed classes and interfaces

<table>
  <tr>
    <th>Old interface/class/namespace</th><th>New interface/class/namespace</th>
  </tr>
  <tr>
    <td><a href="../1.0/Doctrine/Admin/Context"><code>FSi\Bundle\AdminBundle\Doctrine\Admin\Context</code></a></td>
    <td><a href="Admin/CRUD/Context"><code>FSi\Bundle\AdminBundle\Admin\CRUD\Context</code></a></td>
  </tr>
  <tr>
    <td><a href="../1.0/Admin/Doctrine"><code>FSi\Bundle\AdminBundle\Admin\Doctrine</code></a></td>
    <td><a href="Doctrine/Admin"><code>FSi\Bundle\AdminBundle\Doctrine\Admin</code></a></td>
  </tr>
  <tr>
    <td><a href="../1.0/Admin/Context/ContextManagerInterface.php"><code>FSi\Bundle\AdminBundle\Admin\Context\ContextManagerInterface</code></a></td>
    <td><a href="Admin/Context/ContextManager.php"><code>FSi\Bundle\AdminBundle\Admin\Context\ContextManager</code></a></td>
  </tr>
  <tr>
    <td><a href="../1.0/Controller/CRUDController.php"><code>FSi\Bundle\AdminBundle\Controller\CRUDController</code></a></td>
    <td>
      <a href="Controller/ListController.php"><code>FSi\Bundle\AdminBundle\Controller\ListController</code></a>,
      <a href="Controller/FormController.php"><code>FSi\Bundle\AdminBundle\Controller\FormController</code></a>,
      <a href="Controller/BatchController.php"><code>FSi\Bundle\AdminBundle\Controller\BatchController</code></a>
    </td>
  </tr>
  <tr>
    <td><a href="../1.0/Menu/MenuBuilder.php"><code>FSi\Bundle\AdminBundle\Menu\MenuBuilder</code></a></td>
    <td><a href="Menu/Builder.php"><code>FSi\Bundle\AdminBundle\Menu\Builder</code></a></td>
  </tr>
</table>

## Deprecated classes and interfaces

<table>
  <tr>
    <th>Old interface/class/namespace</th><th>New interface/class/namespace</th>
  </tr>
  <tr>
    <td><a href="Admin/ElementInterface.php"><code>FSi\Bundle\AdminBundle\Admin\ElementInterface</code></a></td>
    <td><a href="Admin/Element.php"><code>FSi\Bundle\AdminBundle\Admin\Element</code></a></td>
  </tr>
  <tr>
    <td><a href="Admin/CRUD/CRUDInterface.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\CRUDInterface</code></a></td>
    <td><a href="Admin/CRUD/CRUDElement.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\CRUDElement</code></a></td>
  </tr>
  <tr>
    <td>
      <a href="Admin/CRUD/DataGridAwareInterface.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface</code></a>,
      <a href="Admin/CRUD/DataSourceAwareInterface.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface</code></a>
    </td>
    <td><a href="Admin/CRUD/ListElement.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\ListElement</code></a></td>
  </tr>
  <tr>
    <td><a href="Admin/CRUD/FormAwareInterface.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\FormAwareInterface</code></a></td>
    <td><a href="Admin/CRUD/FormElement.php"><code>FSi\Bundle\AdminBundle\Admin\CRUD\FormElement</code></a></td>
  </tr>
  <tr>
    <td>
      <a href="Admin/ResourceRepository/ResourceInterface.php"><code>FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceInterface</code></a>,
      <a href="Admin/ResourceRepository/AbstractResource.php"><code>FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource</code></a>
    </td>
    <td><a href="Admin/ResourceRepository/Element.php"><code>FSi\Bundle\AdminBundle\Admin\ResourceRepository\Element</code></a></td>
  </tr>
  <tr>
    <td><a href="Doctrine/Admin/CRUDInterface.php"><code>FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDInterface</code></a></td>
    <td><a href="Doctrine/Admin/Element.php"><code>FSi\Bundle\AdminBundle\Doctrine\Admin\Element</code></a></td>
  </tr>
  <tr>
    <td><a href="Doctrine/Admin/DoctrineAwareInterface.php"><code>FSi\Bundle\AdminBundle\Doctrine\Admin\DoctrineAwareInterface</code></a></td>
    <td><a href="Doctrine/Admin/Element.php"><code>FSi\Bundle\AdminBundle\Doctrine\Admin\Element</code></a></td>
  </tr>
</table>
