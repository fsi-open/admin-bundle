# Embedding elements

This section will show you how to embed a CRUD list under an edit form of another admin element.
Let's assume we have a [User admin element](admin_element_crud.md) and need to display a
list of user invoices under the edit form.

First of all we need to prepare the relative entity classes:

## User entity

```php
<?php
// src/Acme/UserBundle/Entity/User.php

namespace Acme\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fsi_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="UserInvoice", mappedBy="user")
     */
    private $invoices;

    public function __construct()
    {
        parent::__construct();
        $this->invoices = new ArrayCollection();
    }

    /**
     * @return Collection|UserInvoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(UserInvoice $invoice): void
    {
        $invoice->setUser($this);
        $this->invoices->add($invoice);
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
 * @ORM\Table(name="fsi_user_invoice")
 */
class UserInvoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invoices")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
```

Having that, you need to update your database schema. To do that, you can use the command below:

```
$ php app/console doctrine:schema:update --force
```

Now that the database is ready, we need to create an admin element for `UserInvoice`s.
This element is pretty peculiar - it should not be displayed in menu nor should it allow for deleting/adding/editing invoices.
Because of that, we cannot mark it as an admin element with neither service tag or class annotation.
Also, it should only work for specific instances of the `User` entity.

Here's how an `UserInvoice` element can look like:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/UserInvoice

namespace Acme\DemoBundle\Admin;

use AcmeDemoBundle\Entity;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInvoice extends ListElement
{
    public function getClassName(): string
    {
        return Entity\UserInvoice::class;
    }

    public function getId(): string
    {
        return 'invoices';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'user' => Entity\User::class
        ));

        $resolver->setRequired(array(
            'user'
        ));
    }

    public function getUserId(): int
    {
        return $this->getOption('user')->getId();
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
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

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid('datagrid');

        $datagrid->addColumn('title', 'text', array(
            'label' => 'Title'
        ));

        return $datagrid;
    }
}
```

Since the element requires the ``user`` option, we need to create it somewhere where a `User` instance is available.
The best place would be inside of `User` admin element, since there we have the access to the `DataGridFactory`,
`DataSourceFactory` and Doctrine `ManagerRegistry`:

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace Acme\DemoBundle\Admin;

use Acme\DemoBundle\Entity;
use Acme\DemoBundle\Admin\UserInvoice;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;

class User extends CRUDElement
{
    public function getClassName(): string
    {
        return Entity\User::class;
    }

    public function getId(): string
    {
        return 'users';
    }

    public function invoices(Entity\User $user): UserInvoice
    {
        $invoice = new UserInvoice(array(
            'user' => $user,
            'template_crud_list' => '@AcmeDemo/Admin/user_invoices_list.html.twig'
        ));

        $invoice->setManagerRegistry($this->registry);
        $invoice->setDataSourceFactory($this->datasourceFactory);
        $invoice->setDataGridFactory($this->datagridFactory);

        return $invoice;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array(
            'entity' => $this->getClassName()
        ), 'datasource');

        $datasource->addField('email', 'text', 'like');
        $datasource->addField('username', 'text', 'like');

        // Add more fields if you need them
        // To get more information about datasource you should visit https://github.com/fsi-open/datasource

        return $datasource;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('datagrid');

        $datagrid->addColumn('email', 'text', array(
            'label' => 'Email'
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
                    'route_name' => 'fsi_admin_form',
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
            'data_class' => Entity\User::class // this option is important for creation form
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

Next, we have to modify the template for the `User` edit form:

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

and add it to the element's service options:

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

Then, we have to prepare the `UserInvoice` element list template:

```twig
{% extends '@FSiAdmin/List/list.html.twig' %}

{% block themes %}
    {% datasource_route datasource_view 'fsi_admin_form' with {'element' : 'users', 'id' : element.userId} %}
    {% datasource_theme datasource_view admin_templates_datasource_theme %}
    {% datagrid_theme datagrid_view admin_templates_datagrid_theme with {'datasource' : datasource_view, 'element' : element.id} %}
{% endblock themes %}
```

This is enough for displaying a list of user invoices under his/hers edition form (assuming he/she has any).
But since we rendered it through the ``render(controller( .. ))`` method, the pagination will not
work - it does not have the access to the application's main request object.

So, how we are going to take data from the main request and pass it to the datasource
rendered in a subrequest? Via the admin bundle events, of course!

First, we need to create an event listener class:

```php
<?php
// src/Acme/DemoBundle/EventListener/Admin/UserListener.php
namespace Acme\DemoBundle\EventListener\Admin;

use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;

class UserListener
{
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

Then we register it in the `services.xml` file:

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

And everything should work now!

[Back to index](index.md)
