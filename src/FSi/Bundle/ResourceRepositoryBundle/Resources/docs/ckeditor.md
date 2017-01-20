# CKEditor Wysiwyg

WYSIWYG (What you see is what you get) editor.
To use ``ckeditor`` resource type you need to register ``egeloen/ckeditor-bundle`` that provide ``ckeditor``
form type.

## 1. Composer
Add to composer.json following lines

```
"require": {
    "egeloen/ckeditor-bundle" : "~2.5"
}
```

## 2. Application Kernel
 
Register bundle in AppKernel  
**IMPORTANT!!** make sure that ``Ivory\CKEditorBundle\IvoryCKEditorBundle()`` is registered
**before** ``FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()``. In other way you will not be able
to use ckeditor resource type.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

        // FSiResourceRepositoryBundle must be after IvoryCKEditorBundle

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
            type: ckeditor
            form_options:
                label: Content
```

To read about ckeditor form type options go to [IvoryCKEditorBundle](https://github.com/egeloen/IvoryCKEditorBundle/blob/master/Resources/doc/configuration.md)
