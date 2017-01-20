# Text

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
                    <li>trim</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>boolean</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><code>false</code></li>
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

### Usage example

Input class

```php
class User
{
    /* @var string */
    public $name;

    /* @var string */
    public $surname
}
```

======
#### Example 1

**Column Configuration**
```php
$datagrid->addColumn('name', 'text');
```

**Input**
```php
$user = new User();
$user->name = 'Norbert';
```

**Output**
> Norbert

======
#### Example 2

**Column Configuration**
```php
$datagrid->addColumn('name_surname', 'text', array(
    'field_mapping' => array(
        'name',
        'surname'
    ),
    'value_glue' => '-'
));
```

**Input**
```php
$user = new User();
$user->name = 'Norbert';
$user->surname = 'Orzechowicz';
```

**Output**
> Norbert-Orzechowicz

======
#### Example 3

**Column Configuration**
```php
$datagrid->addColumn('name_surname', 'text', array(
    'field_mapping' => array(
        'name',
        'surname'
    ),
    'editable' => true,
    'form_options' => array( // Optional option used to configure forms used to edit fields
        'name' => array(
            'label' => 'Name',
            'required' => false
        ),
        'surname' => array(
            'label' => 'Surname'
            'required' => false
        )
    ),
    'form_types' => array( // Optional option used to change form types used to edit fields
        'name' => 'text',
        'surname' => 'text'
    )
));
```

======
