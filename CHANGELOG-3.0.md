# CHANGELOG for 3.0

This document covers the extent of changes done during the transition from
branches 2.* to 3.0.

## Symfony 3 support

This bundle now supports Symfony 3.

## Removed deprecated classes and options from version 1.1

Everything marked as deprecated in 1.1 has been finally removed in this version.
Refer to [1.1 changelog](CHANGELOG-1.1.md) to see what was deleted.

## Removed deprecated aliases and parameters from version 2.1

There were inconsistent service definitions IDs for display and resource contexts
and these were replaced with ones matching the rest of services. Below is a list
of replaced services and parameters:

<table>
    <thead>
        <tr>
            <th>2.1</th>
            <th>3.*</th>
        </tr>
        <tr>
            <th>Deprecated value</th>
            <th>New value</th>
        </tr>  
    </thead>
    <tbody>
        <tr>
            <td>%admin.display.context.class%</td>
            <td>%admin.context.display.class%</td>
        </tr>
        <tr>
            <td>%admin.display.context%</td>
            <td>%admin.context.display%</td>
        </tr>
        <tr>
            <td>%admin.display.context.request_handler.class%</td>
            <td>%admin.context.display.request_handler.class%</td>
        </tr>
        <tr>
            <td>%admin.display.context.request_handler%</td>
            <td>%admin.context.display.request_handler%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.class%</td>
            <td>%admin.context.resource.class%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.form_builder.class%</td>
            <td>%admin.context.resource.form_builder.class%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.request_handler.form_submit.class%</td>
            <td>%admin.context.resource.request_handler.form_submit.class%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.request_handler.form_valid_request.class%</td>
            <td>%admin.context.resource.request_handler.form_valid_request.class%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.form_builder%</td>
            <td>%admin.context.resource.form_builder%</td>
        </tr>
        <tr>
            <td>%admin.resource.context%</td>
            <td>%admin.context.resource%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.request_handler.form_submit%</td>
            <td>%admin.context.resource.request_handler.form_submit%</td>
        </tr>
        <tr>
            <td>%admin.resource.context.request_handler.form_valid_request%</td>
            <td>%admin.context.resource.request_handler.form_valid_request%</td>
        </tr>
    </tbody>
</table>

## Removed CRUD/form.html.twig

This template is never used and was only left for backwards compatibility's sake.
Now it is removed.

## ControllerAbstract gets the EventDispatcher through the constructor

[ControllerAbstract](Controller/ControllerAbstract.php) now has the `EventDispatcher`
passed into it's constructor instead through the `setEventDispatcher` method. Due to
this change, the [compiler pass](DependencyInjection/Compiler/SetEventDispatcherPass.php)
became redundant and was removed.

## Default response templates are injected into contexts

Previously default response templates were injected into controllers and could have
been overwritten by a value returned by the context handling the element. This
behaviour split the responsibilty of providing the template name between two 
classes, which caused inconsitency and was problematic, since controllers can
handle vastly different elements. Now all contexts are required to provide a template
name, assuming their action returns an HTML response. For example Batch actions
will return a redirect response, so there is no point for them to provide one.

## Resolving Admin\AbstractElement options only on first use

Until now options passed into [AbstractElement](Admin/AbstractElement.php) were
resolved immediately in the class' constructor, but now it is postponed until
the first use of `getOption`, `hasOption` or `getOptions` methods.

The `$options` property has also been made private to ensure they are not resolved
somewhere else in extending classes, so if you access it directly in your elements, 
you will need to use the appropriate getter method instead.

## Removed deprecated AbstractCRUD element

[AbstractCRUD](Admin/CRUD/AbstractCRUD.php) was marked as deprecated and was removed
in favour of [GenericCRUDElement](Admin/CRUD/GenericCRUDElement.php).

## Renamed setDefaultOptions to configureOptions in Element interface

[Element](Admin/Element.php) has had the `setDefaultOptions` method renamed
to `configureOptions`, mirroring the change of Symfony's form component.

## Refactored Display component

It was simplified to make it more accessible to use. The `\FSi\Bundle\AdminBundle\Display\DisplayView`
and `FSi\Bundle\AdminBundle\Display\View` classes were dropped and now the data between 
[Display](Display/Display.php) and [DisplayContext](Admin/Display/Context/DisplayContext.php)
is passed directly. Also, the data formatters are applied immediately after the
value is added to the [Display](Display/Display.php), instead during creation of
the `FSi\Bundle\AdminBundle\Display\DisplayView`.

Please refer to [Display components documentation](Resources/doc/admin_element_display.md) for
more information.

## BatchFormValidRequestHandler requires FlashMessages

As of this version, `FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\BatchFormValidRequestHandler`
requires the `FlashMessages` as the third constructor parameter.

## Added events fired before and after applying a batch action

Two news events are being fired when each objects has an batch action applied to
it. The events are:

<table>
    <thead>
        <tr>
            <th>Event name</th>
            <th>Event class</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>admin.batch.object.pre_apply</td>
            <td>FSi\Bundle\AdminBundle\Event\BatchPreApplyEvent</td>
        </tr>
        <tr>
            <td>admin.batch.object.post_apply</td>
            <td>FSi\Bundle\AdminBundle\Event\BatchEvent</td>
        </tr>
    </tbody>
</table>    

The event class `FSi\Bundle\AdminBundle\Event\BatchPreApplyEvent` also has a property
called `skip`, which you can use to mark an object to be skipped when applying actions.

## Deprecated annotation as service registration for elements

This option was introduced to make admin elements registration easier. With the 
introduction of service configuration defaults in the dependency injection component,
it has became redundant and will be removed.

## Dropped routes deprecated back in 1.1

Refer to [1.1 changelog](CHANGELOG-1.1.md) for information on which routes have been removed.

## Dropped support for PHP below 7.1

To be able to fully utilize new functionality introduced in 7.1, we have decided
to only support PHP versions equal or higher to it.
