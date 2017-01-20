# FSi Wysiwyg (deprecated)

**IMPORTANT!!** This way of integrating CKEditor with Resource Repository Bundle is deprecated, use [ckeditor](ckeditor.md) instead.

WYSIWYG (What you see is what you get) editor.
To use ``fsi_ckeditor`` resource type you need to register ``fsi/form-extensions-bundle`` that provide ``fsi_ckeditor``
form type.

## 1. Composer
Add to composer.json following lines

```
"require": {
    "fsi/form-extensions-bundle" : "1.0.*"
}
```

## 2. Application Kernel
 
Register bundle in AppKernel  
**IMPORTANT!!** make sure that ``FSi\Bundle\FormExtensionsBundle\FSiFormExtensionsBundle()`` is registered
**before** ``FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()``. In other way you will not be able
to use fsi_ckeditor resource type.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new FSi\Bundle\FormExtensionsBundle\FSiFormExtensionsBundle(),

        // FSiResourceRepositoryBundle must be after FSiFormExtensionsBundle

        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()
    );

    return $bundles;
}
```

Example:

```yaml
# app/config/resource_map.yml

resources:
    type: group
    home_page:
        type: group
        content:
            type: fsi_ckeditor
            form_options:
                label: Content
```

To read about fsi_ckeditor form type options go to [FSiFormExtensionsBundle](https://github.com/fsi-open/form-extensions-bundle/blob/master/Resources/doc/fsi_ckeditor.md)
