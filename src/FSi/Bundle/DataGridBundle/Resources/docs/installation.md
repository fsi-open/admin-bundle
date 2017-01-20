# Installation

## Download DataGridBundle
Add to composer.json

```
"require": {
    "fsi/datagrid-bundle": "1.0.*
}
```

## Enable the bundle
```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
    );
}
```

That's all, now you should read something about [basic usage](basic_usage.md)