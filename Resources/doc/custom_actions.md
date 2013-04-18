# Custom Actions

## Single row action

As you start to implement FSiAdminBundle in your application, you will probably find that you need to expand default actions.
First example that illustrates the problem is how to add custom action to DataGrid.
By default DataGrid has edit/moveup/movedown action but sometimes its important to add something specific like
disable action.

This can be done by [overriding bundle](Resources/doc/installation.md) and expading CRUDController.
Lets assume we need to add "activate/deactivate" button at news list.
First lets take a look at News entity.

```php
<?php

namespace FSi\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="news")
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     **/
    protected $active;

    // other fields here

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return News
     */
    public function setActive($active = true)
    {
        $this->active = $active;

        return $this;
    }
}
```

As we can see there is a $active field that we want to switch directly from news list. Of course this can be done
by adding active field into edit form.

First we need to create new action in our overrided CRUDController.

```php
// src/FSi/AdminDemoBundle/Controller/CRUDController.php
<?php

namespace FSi\AdminDemoBundle\Controller;

use FSi\Bundle\AdminBundle\Controller\CRUDController as BaseController;
use FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CRUDController extends BaseController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface $element
     * @param $id
     * @return mixed
     *
     * @Route("/admin/{element}/activation/{id}", name="fsi_admin_crud_activation")
     */
    public function activationAction(Request $request, Doctrine\AdminElementInterface $element, $id)
    {
        $entity = $element->getDataIndexer()->getData($id);

        if (!isset($entity)) {
            return $this->createNotFoundException();
        }

        $entity->setActive(!$entity->isActive());

        $element->getObjectManager()->persist($entity);
        $element->getObjectManager()->flush();

        if ($request->query->has('redirect_uri')) {
            return $this->redirect($request->query->get('redirect_uri'));
        }

        return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
    }
}
```

As you can see this action is hardly bounded to entities with ``isActive`` and ``setActive``. Of course you can add
some conditions that will check $element->getId() result and throw exception but this depends on specific use case.

Now you need to modify routing.yml

```
# app/config/routing.yml

admin_demo:
    resource: "@FSiAdminDemoBundle/Controller/"
    type:     annotation

```

Now at least we can decide where to add this action at list, we will consider use case where activation/deactivation is
just a button in cell at list.

```php
// src/FSi/DemoBundle/Admin/News.php
<?php
namespace FSi\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Structure\Doctrine\AbstractAdminElement;

class News extends AbstractAdminElement
{
    /**
     * {@inheritDoc}
     */
    protected function initDataGrid()
    {
        $datagrid = $this->getDataGridFactory()->createDataGrid('news_grid');
        $router = $this->router;
        $element = $this->getId();
        $datagrid->addColumn('active', 'action', array(
            'label' => 'news.datagrid.active.label',
            'field_mapping' => array('id', 'active'),
            'translation_domain' => 'messages',
            'actions' => array(
                'active' => array(
                    'url_attr' => function($values, $index) {
                        return array(
                            'class' => !$values['active']
                                ? 'btn btn-small-horizontal'
                                : 'btn btn-success btn-small-horizontal',
                            'title' => $values['active']
                                ? 'crud.list.datagrid.action.disable'
                                : 'crud.list.datagrid.action.active'
                        );
                    },
                    'content' => function($values, $index) {
                        return !$values['active']
                            ? '<span class="icon-off"></span>'
                            : '<span class="icon-ok icon-white"></span>';
                    },
                    'route_name' => 'fsi_admin_crud_activation',
                    'parameters_field_mapping' => array(
                        'id' => function($values, $index) {
                            return $index;
                        }
                    ),
                    'additional_parameters' => array(
                        'element' => $this->getId()
                    )
                )
            )
        ));
    }
}
```

## Multiple rows action

You also might want to add action that can be called for multiple rows in one time, just like delete action.

1. modify news admin object configuration:

```yaml
# app/config/config.yml

fsi_admin:
    groups:
        admin.group.basic_elements :
            elements:
                admin.element.news:
                    options:
                        template_crud_list: "@FSiAdminDemo/CRUD/list.html.twig"
```

2. Create custom list view with overrided batch_action block

```
{# src/FSi/AdminDemoBundle/Resources/views/CRUD/list.html.twig #}
{% extends "@FSiAdmin/CRUD/list.html.twig" %}

{% block batch_action %}
    {% if element.option('allow_delete') == true and datagrid_view.hasColumnType('batch') == true %}
        <select id="batch_action" class="pull-left">
            <option>{{ 'crud.list.batch.empty_choice'|trans({}, 'FSiAdminBundle') }}</option>
            <option value="{{ path('fsi_admin_crud_multi_activation', {element : element.id}) }}">{{ 'crud.list.batch.activation'|trans }}</option>
            <option value="{{ path('fsi_admin_crud_delete', {element : element.id}) }}">{{ 'crud.list.batch.delete'|trans({}, 'FSiAdminBundle') }}</option>
        </select>
    {% endif %}
{% endblock batch_action %}
```

3. Create action ``fsi_admin_crud_multi_activation`` in controller

```php
// src/FSi/AdminDemoBundle/Controller/CRUDController.php
<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\AdminDemoBundle\Controller;

use FSi\Bundle\AdminBundle\Controller\CRUDController as BaseController;
use FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface as DoctrineAdminElementInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CRUDController extends BaseController
{
    /**
     * @param Request $request
     * @param DoctrineAdminElementInterface $element
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/admin/{element}/multiactivation", name="fsi_admin_crud_multi_activation", requirements={"_method": "POST"})
     */
    public function multiActivationAction(Request $request, DoctrineAdminElementInterface $element)
    {
        //indexes shoule be always passed as array under ``indexes`` key in request. 
        $indexes = $request->request->get('indexes', array());
        $indexer = $element->getDataIndexer();
        if (count($indexes)) {
            foreach ($indexes as $index) {
                $entity = $indexer->getData($index);
                if (!isset($entity)) {
                    return $this->createNotFoundException();
                }

                $entity->setActive(true);
                $element->getObjectManager()->persist($entity);
                $element->getObjectManager()->flush();
            }
        }

        return $this->redirect($this->generateUrl('fsi_admin_crud_list', array('element' => $element->getId())));
    }
}
```