# Overriding Default AdminBundle Controllers



The default controllers packaged with the FSiAdminBundle provide a lot of functionality that enough for general use cases.
But, you might find that you need to extend that functionality and add some logic that suits the specific needs of your application.

**Important**
> Overriding the controller requires to duplicate all the logic of the action.
> Replacing the whole controller should be considered as the last solution when nothing else is possible.

The first step to overriding a controller in the bundle is to create a child bundle whose parent is FSiAdminBundle.

```php
// src/FSi/AdminDemoBundle/FSiAdminDemoBundle.php
<?php

namespace FSi\AdminDemoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminDemoBundle extends Bundle
{
    public function getParent()
    {
        return 'FSiAdminBundle';
    }
}
```

**Important**
> The Symfony2 framework only allows a bundle to have one child.
> You cannot create another bundle that is also a child of FSiAdminBundle.

You also nee to register bundle in AppKernel.php

```php
// app/AppKernel.php
<?php

use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new FSi\Bundle\AdminBundle\FSiAdminBundle(),
            new FSi\AdminDemoBundle\FSiAdminDemoBundle(),
            // ...
        );
    }
}

```

Now you can create custom controller that will override CRUDController (or any other).

```
// src/FSi/AdminDemoBundle/Controller/CRUDController.php
<?php

namespace FSi\AdminDemoBundle\Controller;

use FSi\Bundle\AdminBundle\Controller\CRUDController as BaseController;
use FSi\Bundle\AdminBundle\Structure\AdminElementInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CRUDController extends BaseController // BaseController is nothing more than oryginal CrudController, check use statement.
{
    /**
     * @param Request $request
     * @param AdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function listAction(Request $request, AdminElementInterface $element)
    {
        if (!$element->hasDataGrid() || !$element->hasDataSource()) {
            throw $this->createNotFoundException();
        }

        $datasource = $element->getDataSource();
        $datagrid = $element->getDataGrid();
        $datasource->bindParameters($request);
        $data = $datasource->getResult();
        $datagrid->setData($data);

        if ($request->isMethod('POST'))  {
            $datagrid->bindData($request);
            $element->saveGrid();

            $datasource->bindParameters($request);
            $data = $datasource->getResult();
            $datagrid->setData($data);
        }

        $template = $this->container->getParameter('admin.templates.crud_list');
        return $this->render($template, array(
            'elements_count' => count($data),
            'element' => $element,
            'datasource_view' => $datasource->createView(),
            'datagrid_view' => $datagrid->createView()
        ));
    }
}

```