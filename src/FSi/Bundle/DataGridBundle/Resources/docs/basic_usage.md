# Basic usage.

## Configure DataGrid fields in .yml file

Configuration must be stored in .yml file with name equal to datagrid name in
``src/<Bundle_Path>/Resources/config/datagrid/<datagrid_name>.yml``

```
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/news.yml
columns:
  id:
    type: number
    options:
      label: Identity
  title:
    type: text
    options:
       editable: true
  author:
    type: text
    options:
       editable: true
```

```
<?php
// src/FSi/Bundle/DemoBundle/Controller/DefaultController.php 

namespace FSi\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="demo_index")
     * @Template()
     */
    public function indexAction()
    {
        $array = $this->getDoctrine()->getManager()
            ->getRepository('FSiSiteBundle:News')
            ->findAll();

        $dataGrid = $this->get('datagrid.factory')->createDataGrid('news');
        $dataGrid->setData($array);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $dataGrid->bindData($request);
            $this->getDoctrine()->getManager()->flush();
        }

        return array(
            'datagrid' => $dataGrid->createView()
        );
    }
}
```

## Configure DataGrid fields in php code

```
<?php
// src/FSi/Bundle/DemoBundle/Controller/DefaultController.php 

namespace FSi\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="demo_index")
     * @Template()
     */
    public function indexAction()
    {
        $array = $this->getDoctrine()->getManager()
            ->getRepository('FSiSiteBundle:News')
            ->findAll();

        $dataGrid = $this->get('datagrid.factory')->createDataGrid();
        $dataGrid->addColumn('id', 'number', array(
            'label' => 'identity'
        ));
        $dataGrid->addColumn('title', 'text', array(
            'editable' => true
        ));
        $dataGrid->addColumn('author', 'text', array(
            'editable' => true
        ));

        $dataGrid->setData($array);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $dataGrid->bindData($request);
            $this->getDoctrine()->getManager()->flush();
        }

        return array(
            'datagrid' => $dataGrid->createView()
        );
    }
}
```

You can read more about available column types [here](columns.md).

## Display DataGrid in twig template

```
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

<div class="table-border">
    <form action="{{ path('demo_index') }}" method="post">
    {{ datagrid_widget(datagrid) }}
    </form>
</div>
```

Form is used to handle "edit at list" feature.


You should read [DataGrid Component Readme](https://github.com/fsi-open/datagrid) to learn more
about columns and their options.

[How to overwrite default templates?](templating.md)
