# Basic usage

## Configure DataSource fields in .yml file

Configuration must be stored in .yml file with name equal to datasource name in
``src/<Bundle_Path>/Resources/config/datasource/<datasource_name>.yml``

```
# src/FSi/Bundle/DemoBundle/Resources/config/datasource/news.yml
fields:
  title:
    type: text
    comparison: like
  author:
    type: text
    comparison: like
  id:
    type: number
    comparison: eq
    options:
       ordering: desc
       form_disabled: true
  createdate:
    type: datetime
    comparison: between
    options:
      form_options:
        date_widget: single_text
        time_widget: single_text
  category:
    type: entity
    comparison: in
    options:
      form_options:
        class: FSi\Bundle\DemoBundle\Entity\Category
      ordering_disabled: true
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
        $factory = $this->get('datasource.factory');
        $driverOptions = array(
            'entity' => 'FSiDemoBundle:News'
        );
        $datasource = $factory->createDataSource('doctrine-orm',  $driverOptions, 'datasource_name');

        $dataSource->bindParameters($this->getRequest());

        return array(
            'datasource' => $dataSource->createView(),
            'data' => $dataSource->getResult()
        );
    }
}
```

## Configure DataSource in php code

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
        $factory = $this->get('datasource.factory');
        $driverOptions = array(
            'entity' => 'FSiDemoBundle:News'
        );
        $datasource = $factory->createDataSource('doctrine-orm',  $driverOptions, 'datasource_name');

        $datasource->addField('title', 'text', 'like')
            ->addField('author', 'text', 'like')
            ->addField('id', 'number', 'eq', array(
                'ordering' => 'desc',
                'form_disabled' => true
            ))
            ->addField('createdate', 'datetime', 'between', array(
                'form_options' => array(
                    array(
                        'date_widget' => 'single_text',
                        'time_widget' => 'single_text',
                    )
                )
            ))
            ->addField('category', 'entity', 'in', array(
                'form_options' => array(
                    'class' => 'FSi\Bundle\SiteBundle\Entity\Category',
                ),
                'ordering_disabled' => true,
            ));

        $dataSource->bindParameters($this->getRequest());

        return array(
            'datasource' => $dataSource->createView(),
            'data' => $dataSource->getResult()
        );
    }
}

```

## Display DataSource parts in twig template

Display filters

```
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

<form action="{{ path('demo_index') }}" method="post">
    {{ datasource_filter_widget(datasource) }}
</form>
```

Display pagination

```
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

<div class="pagination">
    {{ datasource_pagination_widget(datasource, {max_pages: 10, active_class: 'active', disabled_class: 'disabled' }) }}
</div>
```

Display sortable buttons

```
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

{{ datasource_sort_widget(datasource.getField(field_name)) }}
```

[How to overwrite default templates?](templating.md)
