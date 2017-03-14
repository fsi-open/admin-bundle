# UPGRADE from ~1.1/2.0.*

## Make sure allow_add and allow_delete options are properly used

As of version 2.1 these are properly verified in relevant actions. If you have,
for example, set one of these options to false on an element, but somehow overwritten
the default behaviour so the action can be performed, it will result in an error.

## Do not use deprecated services and parameters IDs for ResourceRepository and Display contexts

For the sake of consistency, all context services have the same schema for their IDs.
If you relied on any of these, please attempt to replace them with new versions, as
they will be removed in version 3. For the extent of changes please refer to the
[changelog](CHANGELOG-2.1.md#deprecated-inconsintent-service-definitions-and-parameters-for-resourcerepository-and-display-contexts).
