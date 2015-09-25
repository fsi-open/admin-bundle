# Embedding element

This section will show you how to embed CRUD list under edit form of other admin element.
Lets assume that we have a [User admin element](admin_element_crud.md) and we need to display
list of user invoices under edit form.

First of all we need to prepare entities.

## User entity

```php
<?php
// src/Acme/UserBundle/Entity/User.php

namespace Acme\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="UserInvoice", mappedBy="user")
     */
    protected $invoices;

    public function __construct()
    {
        parent::__construct();
        $this->invoices = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    public function addInvoice(UserInvoice $invoice)
    {
        $invoice->setUser($this);
        $this->invoices->add($invoice);

        return $this;
    }
}
```

## User invoice entity

```php
<?php
// src/Acme/UserBundle/Entity/UserInvoice.php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user_invoice")
 */
class UserInvoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invoices")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}
```

```
$ php app/console doctrine:schema:update --force
```

Database is ready. Now we need to create admin element for UserInvoices. This element is specific because it should
not be displayed in menu and should not allow you to delete/add/edit invoices.
Because of those requirements we can't create it as a service with tag "admin.element". Also this element
should be created only for specific User. Ok, so lets start with ``UserInvoice`` element.

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/UserInvoice

namespace Acme\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserInvoice extends CRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'AcmeDemoBundle:UserInvoice';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'invoices';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'user' => 'Acme\DemoBundle\Entity\User'
        ));

        $resolver->setRequired(array(
            'user'
        ));
    }

    public function getUserId()
    {
        return $this->getOption('user')->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->getRepository()
            ->createQueryBuilder('i');

        $qb->andWhere('i.user = :user')
            ->setParameter('user', $this->getOption('user'));

        $datasource = $factory->createDataSource('doctrine', array(
            'qb' => $qb
        ), 'invoices_datasource');

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid('datagrid');

        $datagrid->addColumn('title', 'text', array(
            'label' => 'Title'
        ));

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        // because this element don't allow you create/edit invoices this method can be empty
    }
}
```

If element require from us ``user`` option we need to create it somewhere where user object is available and where
we can set DataGridFactory, DataSourceFactory and Doctrine ManagerRegistry. Probably the best place for this action
is inside of User admin element. Lets modify it:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace Acme\DemoBundle\Admin;

use Acme\DemoBundle\Entity\User as UserEntity;
use Acme\DemoBundle\Admin\UserInvoice;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class User extends CRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'AcmeDemoBundle:User';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'users';
    }

    public function invoices(UserEntity $user)
    {
        $invoice = new UserInvoice(array(
            'allow_delete' => false,
            'allow_add' => false,
            'allow_edit' => false,
            'user' => $user,
            'template_crud_list' => '@AcmeDemo/Admin/user_invoices_list.html.twig'
        ));

        $invoice->setManagerRegistry($this->registry);
        $invoice->setDataSourceFactory($this->datasourceFactory);
        $invoice->setDataGridFactory($this->datagridFactory);

        return $invoice;
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array(
            'entity' => $this->getClassName()
        ), 'datasource');

        $datasource->addField('email', 'text', 'like');
        $datasource->addField('username', 'text', 'like');

        // Here you can add some fields or filters into datasource
        // To get more information about datasource you should visit https://github.com/fsi-open/datasource

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('datagrid');

        $datagrid->addColumn('email', 'text', array(
            'label' => 'Eamil'
        ));
        $datagrid->addColumn('username', 'text', array(
            'label' => 'Username',
            'editable' => true,
        ));
        $datagrid->addColumn('enabled', 'boolean', array(
            'label' => 'Enabled'
        ));
        $datagrid->addColumn('locked', 'boolean', array(
            'label' => 'Locked'
        ));
        $datagrid->addColumn('roles', 'text', array(
            'label' => 'Roles',
            'value_format' => function($data) {
                return implode(', ', $data['roles']);
            }
        ));
        $datagrid->addColumn('action', 'action', array(
            'label' => 'Action',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'url_attr' => array(
                        'class' => 'btn btn-warning btn-small-horizontal',
                        'title' => 'edit user'
                    ),
                    'content' => '<span class="icon-eject icon-white"></span>',
                    'route_name' => 'fsi_admin_crud_edit',
                    'parameters_field_mapping' => array(
                        'id' => 'id'
                    ),
                    'additional_parameters' => array(
                        'element' => $this->getId()
                    )
                )
            )
        ));

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form', $data, array(
            'data_class' => 'Acme\DemoBundle\Entity\User' // this option is important for create form
        ));

        $form->add('email', 'email');
        $form->add('username', 'text');
        $form->add('enabled', 'choice', array(
            'choices' => array(
                0 => 'No',
                1 => 'Yes',
            )
        ));
        $form->add('locked', 'choice', array(
            'choices' => array(
                0 => 'No',
                1 => 'Yes',
            )
        ));

        return $form;
    }
}

```

Now we must modify a bit User edit form template. This can be done by passing path to modified template via
user admin element service options.

```xml
<!-- src/Acme/DemoBundle/Resources/config/services.xml -->
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="acme.demo.admin.user" class="Acme\DemoBundle\Admin\User">
            <argument type="collection">
                <argument key="template_crud_edit">@AcmeDemo/Admin/user_edit.html.twig</argument>
            </argument>
            <tag name="admin.element"/>
        </service>
    </services>
</container>
```

And ``@AcmeDemo/Admin/user_edit.html.twig`` file

```twig
{# src/Acme/DemoBundle/Resources/views/Admin/user_edit.html.twig #}
{% extends '@FSiAdmin/CRUD/edit.html.twig' %}

{% block content %}
{{ parent() }}
<div class="col-lg-12">
    <h1>User invoices</h1>
    {% render(controller('admin.controller.list:listAction', {'element': element.invoices(form.vars.data)})) %}
</div>
{% endblock %}
```

We are almost ready! Next step is to create template for UserInvoice element list. ``@AcmeDemo/Admin/user_invoices_list.html.twig``

```twig
{% extends '@FSiAdmin/List/list.html.twig' %}

{% block themes %}
{% datasource_route datasource_view 'fsi_admin_form' with {'element' : 'users', 'id' : element.userId} %}
{% datasource_theme datasource_view admin_templates_datasource_theme %}
{% datagrid_theme datagrid_view admin_templates_datagrid_theme with {'datasource' : datasource_view, 'element' : element.id} %}
{% endblock themes %}
```

Ok this should be enough to display user invoices under user edit form (of course if user have any invoices).
But pagination or set max result is still not working because controller that is rendered with ``render(controller( .. ))``
don't have access to main app request.

So how we are going to take data from main request and pass it into datasource rendered in sub request? This can be done
with admin bundle events.

First we need to create event class

```php
<?php
// src/Acme/DemoBundle/EventListener/Admin/UserListener.php
namespace Acme\DemoBundle\EventListener\Admin;

use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class UserListener
{
    protected $manager;

    protected $invoicesDatasource;

    public function __construct()
    {
        $this->invoicesDatasource = array();
    }

    public function userEditPostCreate(FormEvent $event)
    {
        if ($event->getElement()->getId() === 'users') {
            $this->invoicesDatasource = $event->getRequest()->get('invoices_datasource', array());
        }
    }

    public function userInvoicesPreDataSourceBindRequest(ListEvent $event)
    {
        if ($event->getElement()->getId() === 'invoices') {
            $event->getDataSource()->bindParameters(array('invoices_datasource' => $this->invoicesDatasource));
        }
    }
}
```

And finally we must register it in services.xml file

```xml
<!-- src/Acme/DemoBundle/Resources/config/services.xml -->
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="acme.demo.admin.user" class="Acme\DemoBundle\Admin\User">
            <argument type="collection">
                <argument key="template_crud_edit">@AcmeDemo/Admin/user_edit.html.twig</argument>
            </argument>
            <tag name="admin.element"/>
        </service>

        <service id="acme.demo.admin.listener.user" class="Acme\DemoBundle\EventListener\Admin\UserListener">
            <tag name="kernel.event_listener" event="admin.crud.edit.context.post_create" method="userEditPostCreate" />
            <tag name="kernel.event_listener" event="admin.crud.list.datasource.request.post_bind" method="userInvoicesPreDataSourceBindRequest" />
        </service>
    </services>
</container>
```

[Back to index](index.md)
