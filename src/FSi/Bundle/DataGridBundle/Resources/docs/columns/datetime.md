# DateTime

<table>
    <head>
        <tr>
            <td><b>Option source</b></td>
            <td><b>Option name</b></td>
            <td><b>Value type</b></td>
            <td><b>Default Value</b></td>
        </tr>
    </head>
    <tbody>
        <tr>
            <td>Options </td>
            <td>
                <ul>
                    <li>datetime_format</li>
                    <li>input_type</li>
                    <li>input_field_format</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string</li>
                    <li>string</li>
                    <li>null|array|string</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><code>Y-m-d H:i:s</code></li>
                    <li><code>null|string|timestamp|datetime|array</code></li>
                    <li><code>null</code></li>
                </ul>
            </td>
        <tr>
        <tr>
            <td>Default Column Options Extension</td>
            <td>
                <ul>
                    <li>label</li>
                    <li>field_mapping</li>
                    <li>display_order</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string</li>
                    <li>array</li>
                    <li>integer|null</li>
                </td>
            </td>
            <td>
                <ul>
                    <li><code>$column->getName()</code></li>
                    <li><code>array($column->getName())</code></li>
                    <li><code>null</code></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Value Format Column Options Extension</td>
            <td>
                <ul>
                    <li>value_glue</li>
                    <li>value_format</li>
                    <li>empty_value</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string|null</li>
                    <li>string|Closure|null</li>
                    <li>string</li>
                </td>
            </td>
            <td>
                <ul>
                    <li><code>null</code></li>
                    <li><code>null</code></li>
                    <li><code>" "</code> (empty string)</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Form Extension</td>
            <td>
                <ul>
                    <li>editable</li>
                    <li>form_options</li>
                    <li>form_type</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>boolean</li>
                    <li>array</li>
                    <li>array</li>
                </td>
            </td>
            <td>
                <ul>
                    <li><code>false</code></li>
                    <li><code>array()</code></li>
                    <li><code>array()</code></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>

** - value translated with ``DataGridBundle`` translation domain

### Usage example

Input class

```php
class User
{
    /* @var \DateTime */
    public $updatedAt;

    /**
     *  Timestamp value
     *  @var int
     */
    public $registeredAt;
}
```
======
#### Example 1

**Column Configuration**
```php
$datagrid->addColumn('updated_at', 'datetime', array(
    'field_mapping' => array('updatedAt'),
    'datetime_format' => 'Y-m-d'
));
```

**Input**
```php
$user = new User();
$user->updatedAt = new \DateTime('2014-04-05 12:41:01');
```

**Output**
> 2014-04-05

======
#### Example 2

**Column Configuration**
```php
$datagrid->addColumn('registered_at', 'datetime', array(
    'field_mapping' => array('registeredAt'),
    'datetime_format' => 'Y-m-d',
    'input_type' => 'timestamp'
));
```

**Input**
```php
$user = new User();
$user->registeredAt = 1396705747;
```

**Output**
> 2014-04-05

======
#### Example 3

**Column Configuration**
```php
$datagrid->addColumn('dates', 'datetime', array(
    'field_mapping' => array(
        'updatedAt',
        'registeredAt'
    ),
    'datetime_format' => array(
        'updatedAt' => 'Y-m-d',
        'registeredAt' => 'Y-m-d H:i:s',
    )
    'input_type' => array(
        'updatedAt' => 'datetime',
        'registeredAt' => 'timestamp',
    ),
    'value_glue' => ' | '
));
```

**Input**
```php
$user = new User();
$user->updatedAt = new \DateTime("2014-02-01");
$user->registeredAt = 1396705747;
```

**Output**
> 2014-02-01 | 2014-04-05 15:49:07

======
