# UPGRADE FROM VERSION 2.1 TO 3.0

## Replace deprecated services and parameters IDs for ResourceRepository and Display

Deprecations marked in [2.1](CHANGELOG-2.1.md#deprecated-inconsintent-service-definitions-and-parameters-for-resourcerepository-and-display-contexts)
are removed, so if you used the old IDs and parameters, you need to change them to
the new ones.

## Pass event dispatcher to controllers' constructor

If you were using the `admin.event_dispatcher_aware` tag in order to inject the
event dispatcher into your services, you will need to change your service definition,
since the relevant compiler pass was removed.

## Inject template name in your custom contexts

If you have created a context, whose actions can return an HTML response, you need
to have it return a default value for the template name. This stems from the change
to controllers [not having](CHANGELOG-3.0.md#default-response-templates-are-injected-into-contexts)
a default template name injected to them.

## Access Admin\AbstractElement options through getter methods

If you have created an element class extending the [AbstractElement](Admin/AbstractElement.php)
and are accessing it's `options` property directly, you will need to switch to
using a proper getter method (`hasOption`, `getOption` or `getOptions`), since it
has been made [private](CHANGELOG-3.0.md#resolving-adminabstractelement-options-only-on-first-use).

## Use GenericCRUDElement instead of AbstractCRUD

Since [AbstractCRUD](Admin/CRUD/AbstractCRUD.php) was removed due to being deprecated,
you will need to use [GenericCRUDElement](Admin/CRUD/GenericCRUDElement.php) instead,
if you have been extending it.

## Use configureOptions instead of setDefaultOptions in admin elements

[Element](Admin/Element.php) method `setDefaultOptions` has been renamed to `configureOptions`,
so you are required to change all implementations of that method in your elements.

## Adjust Display elements

Following the refactoring of the Display component, following classes were removed:

- FSi\Bundle\AdminBundle\Display\DisplayView
- FSi\Bundle\AdminBundle\Display\ObjectDisplay (replaced with [PropertyAccessDisplay](Display/PropertyAccessDisplay.php))
- FSi\Bundle\AdminBundle\Display\View

Also, the [Display](Display/Display.php) interface has been changed. The `add`
method no longer permits empty labels and the `createView` has been removed in
favour of `getData`. Classes implementing it should return an array of [Property](Display/Property.php)
objects. There have been new base classes introduced:

- [PropertyAccessDisplay](Display/PropertyAccessDisplay.php)
- [SimpleDisplay](Display/SimpleDisplay.php)

Please refer to [Display components documentation](Resources/doc/admin_element_display.md) for
more information.

## Always pass FlashMessages to BatchFormValidRequestHandler subclasses

If you have extended the `FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\BatchFormValidRequestHandler`
class, make sure to pass a `FlashMessage` service to it's parent constructor, otherwise you
may receive an exception when handling batch actions.

## Do not use routes deprecated back in version 1.1

Refer to [1.1 changelog](CHANGELOG-1.1.md) for information on which routes have been removed.

## Upgrade to PHP 7.1 or higher

In order to use this bundle, you will need PHP 7.1 or higher. You will also need to adjust all you classes
that inherit from this bundle's classes (especially admin element classes) that method signatures will match
their parents signatures.

## Correct arguments order in calls to FlashMessages methods

All public methods of `FSi\Bundle\AdminBundle\Message\FlashMessages` now has the following arguments order:
`string $message, array $params = [], string $domain = 'FSiAdminBundle'` so be careful to adjust all calls
accordingly.
