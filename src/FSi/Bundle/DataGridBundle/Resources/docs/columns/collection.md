# Collection

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
                    <li>collection_glue</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><code>' '</code></li>
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

    /* @var array */
    public $roles
}
```

======
#### Example 1

**Column Configuration**
```php
$datagrid->addColumn('roles', 'collection');
```

**Input**
```php
$user = new User();
$user->addRole('admin');
$user->addRole('moderator');
$user->addRole('user');
```

**Output**
> admin,user,moderator

======
#### Example 2

**Column Configuration**
```php
$datagrid->addColumn('user_roles', 'collection', array(
    'field_mapping' => array(
        'roles'
    ),
    'collection_glue' => '|'
));
```

**Input**
```php
$user = new User();
$user->addRole('Admin');
$user->addRole('Moderator');
$user->addRole('User');
```

**Output**
> Admin|Moderator|User

======
#### Example 3

**Column Configuration**
```php
$datagrid->addColumn('user_roles', 'text', array(
    'field_mapping' => array(
        'name',
        'roles'
    ),
    'collection_glue' => ',',
    'editable' => true,
    'form_options' => array( // Optional option used to configure forms used to edit fields
        'name' => array(
            'label' => 'Name',
            'required' => false
        ),
        'roles' => array(
            'label' => 'Roles'
            'required' => true
        )
    ),
    'form_types' => array( // Optional option used to change form types used to edit fields
        'name' => 'text',
        'surname' => 'text'
    )
));
```

======
