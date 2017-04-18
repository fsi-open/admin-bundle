# UPGRADE FROM VERSIONS ~1.1/2.0.* TO 2.1

## Make sure allow_add and allow_delete options are properly used

As of version 2.1 these are properly verified in relevant actions. If you have,
for example, set one of these options to false on an element, but somehow overwritten
the default behaviour so the action can be performed, it will result in an error.

## Do not use deprecated services and parameters IDs for ResourceRepository and Display contexts

For the sake of consistency, all context services have the same schema for their IDs.
If you relied on any of these, please attempt to replace them with new versions, as
they will be removed in version 3. For the extent of changes please refer to the
[changelog](CHANGELOG-2.1.md#deprecated-inconsintent-service-definitions-and-parameters-for-resourcerepository-and-display-contexts).

## Ensure BatchFormValidRequestHandler has FlashMessages

Since [BatchFormValidRequestHandler](Admin/CRUD/Context/Request/BatchFormValidRequestHandler.php)
no longer throws an exception if no elements are selected, but displays a warning
message, it needs an instance of [FlashMessages](Message/FlashMessages.php) in order
to do that. If you use your own handler for verifing batch action requests and are
extending the base class, you may want to inject the `admin.messages.flash` service
to your service as well.

The parameter itself is optional, so even if you leave it as it is, no error will
be thrown.

## Use configureOptions in setDefaultOptions in admin elements

[Element](Admin/Element.php) method `setDefaultOptions` is deprecated and will
be removed in 3.0, so it is advised to transist to using `configureOptions` instead.
