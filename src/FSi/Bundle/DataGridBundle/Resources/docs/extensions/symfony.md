# DataGrid Extension SymfonyForm #

## Column Types provided by extension ##

``FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action``

## Event Subscribers provided by extension ##

``FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\EventSubscriber\BindRequest``

## Column Type Extensions provided by extension ##

``FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnTypeExtension\FormExtension``

This extensions is loaded into almost all column types. It allows you to set 
``editable`` option in column type.

If ``editable`` option value is ``true`` the SymfonyForm is being created from
column ``field_mapping``.

**Usage example**

```php
<?php

$grid->addColumn('email', 'text', array(
    'editable' => true  
))
```

Symfony Form View object is available as ColumnView attribute. 

```html
<div>
    <div id="hidden_form">
        <?php 
            $formView = column->GetAttribute('form'); 
            // here you should render form view. 
        ?>
    </div>
    <div id="column_value">
        <?php echo $column->getValue(); ?>
    </div>
</div>
```

**Handling Requests in DataGrid in Symfony2**

```php
<?php

if ($request->getMethod() == 'POST') {
    $grid->bindData($request);
    $this->getDoctrine()->getEntityManager()->flush();
}

```

In most cases this is enough to create grid with some editable fields. 
But sometimes there are situations when you need to pass additional options to form elements. 
This can be achieved with ``form_options`` option that pass options into 
form elements.  
You can also specify form type by ``form_type`` option. 

**Example**
```php
<?php

$grid->addColumn('user_email', 'text', array(
    'mapping_fields' => array('user_email'), //in this case this parameter is optional because column name is same as mapping_field
    'editable' => true,
    'form_options' => array(
        'user_email' => array( //each array key must exist in mapping_fields
            'attr' => array(
                'placeholder' => 'Email..'
            )
        )
    ),
    'form_type' => array(
        'user_email' => 'email'
    )
))
```

This will add column into form with ``type`` email and addition option ``attr``

```php
<?php

$form->add('user_email', 'email', array('attr' => array('placeholder' => ''Email..')));
```
