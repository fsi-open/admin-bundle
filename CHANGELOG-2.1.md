# CHANGELOG FOR VERSION 2.1

This document describes all the significant changes made between 1.1/2.0 and 2.1.

## Added "allow_add" option to Admin\Crud\GenericFormElement

It was a natural consequence of this class and now this option is by default true.

## Properly verified "allow_add" and "allow_delete" options

Previously these options only prevented relevant buttons or batch actions from
being displayed. This has changed, so:

a) Form elements with "allow_add" option set to false will throw a not found exception
   if you try to open the page form without supplying the ID parameter in the request.

b) Elements implementing interface `Admin\CRUD\DeleteElement` with option
   "allow_delete" set to false will throw a logic exception during form submission.

## Deprecated inconsintent service definitions and parameters for ResourceRepository and Display contexts

All context services follow the naming convention: `admin.context.<context name>`,
except for resource and display contexts. These had their IDs changed and the old
versions are only aliases now, pointing at the new ones. Table below shows the 
changeset:

<table>
    <thead>
        <tr>
            <th>1.1/2.0</th>
            <th>2.1</th>
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


## Batch actions do not throw an exception when no elements are submitted

Instead of throwing an exception, a warning message is displayed when no elements
have been submitted in a batch action.
