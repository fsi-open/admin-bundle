## Installation ##

## Download DataSourceBundle
Add to composer.json
```
"require": {
    "fsi/datasource-bundle": "1.2.*"
}
```

## Enable the bundle
```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
    );
}
```

That's all, now you should read something about [basic usage](basic_usage.md)
