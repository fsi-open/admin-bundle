# Entity

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
                    <li>relation_field</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><code>$column->getName()</code></li>
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
            <td>Value Format Column Options Extension (Doctrine)</td>
            <td>
                <ul>
                    <li>glue_multiple</li>
                    <li>value_glue</li>
                    <li>value_format</li>
                    <li>empty_value</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>string</li>
                    <li>string|null</li>
                    <li>string|null</li>
                    <li>array|string|null</li>
                </td>
            </td>
            <td>
                <ul>
                    <li><code>" "</code> (empty string)</li>
                    <li><code>" "</code> (empty string)</li>
                    <li><code>"%s"</code></li>
                    <li><code>null</code></li>
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
class News
{
    /* @var Category[] */
    public $categories;
}
```

```php
class Category
{
    /* @var string */
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
```

======
#### Example 1

**Column Configuration**
```php
$datagrid->addColumn('categories', 'entity', array(
    'relation_field' => 'categories',
    'field_mapping' => array('name'),
    'glue_multiple' => ' | ',
    'value_format' => '%s'
));
```

**Input**
```php
$news = new News();
$news->categories = array(
    new Category('Category Foo'),
    new Category('Category Bar')
);
```

**Output**
> Category Foo | Category Bar

======
#### Example 2

**Column Configuration**
```php
$datagrid->addColumn('categories', 'entity', array(
    'relation_field' => 'categories',
    'field_mapping' => array('name'),
    'glue_multiple' => ' | ',
    'value_format' => '%s',
    'editable' => true,
    'form_type' => array(
        'categories' => 'entity'
    )
    'form_options' => array(
        'categories' => array(
            'class' => Category
            'property' => 'name'
            'expanded' => false,
            'multiple' => true
        )
    )
));
```

======
