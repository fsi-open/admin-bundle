# Boolean

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
                    <li>true_value</li>
                    <li>false_value</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string</li>
                    <li>string</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><code>datagrid.boolean.yes</code> **</li>
                    <li><code>datagrid.boolean.no</code> **</li>
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
    /* @var boolean */
    public $enabled;
}
```
======
#### Example 1

**Column Configuration**
```php
$datagrid->addColumn('enabled', 'boolean');
```

**Input**
```php
$user = new User();
$user->enabled = true;
```

**Output**
> Yes

**Input**
```php
$user = new User();
$user->enabled = false;
```

**Output**
> No

======

