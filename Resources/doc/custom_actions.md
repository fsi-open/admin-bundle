# Custom Actions

As you start to implement FSiAdminBundle in your application, you will probably find that you need to expand default actions.
First example that illustrates the problem is how to add custom action to DataGrid.
By default DataGrid has edit/delete/moveup/movedown action but sometimes its important to add something specific like
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
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    protected function initDataGrid()
    {
        $datagrid = $this->getDataGridFactory()->createDataGrid('news_grid');
        $router = $this->router;
        $element = $this->getId();
        $datagrid->addColumn('active', 'number', array(
            'label' => 'news.datagrid.active.label',
            'field_mapping' => array('id', 'active'),
            'value_format' => function($data) use ($router, $element) {
                $url = $router->generate('fsi_admin_crud_activation', array('element' => $element, 'id' => $data['id']));
                $icon = ($data['active']) ? 'icon-ok' : 'icon-off';

                return sprintf('<a href="%s"><i class="%s"></i></a>', $url, $icon);
            },
        ));
    }
}
