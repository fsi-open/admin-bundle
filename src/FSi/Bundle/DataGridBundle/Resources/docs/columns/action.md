# Action

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
                    <li>
                    actions
                    <ul>
                        <li>route_name</li>
                        <li>redirect_uri</li>
                        <li>absolute</li>
                        <li>url_attr</li>
                        <li>content</li>
                        <li>parameters_field_mapping</li>
                        <li>additional_parameters</li>
                    </ul>
                    </li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>
                    array
                    <ul>
                        <li>string</li>
                        <li>boolean</li>
                        <li>boolean</li>
                        <li>array|Clousure</li>
                        <li>null|string|Clousure</li>
                        <li>array</li>
                        <li>array</li>
                    </ul>
                    </li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>
                    <code>array()</code>
                    <ul>
                        <li>empty <b>(required)</b></li>
                        <li><code>true</code></li>
                        <li><code>false</code></li>
                        <li><code>array()</code></li>
                        <li><code>null</code></li>
                        <li><code>array()</code></li>
                        <li><code>array()</code></li>
                    </ul>
                    </li>
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
    </tbody>
</table>

### Usage example

Input class

```php
class User
{
    /* @var int */
    public $identity;

    /* @var int */
    public $fb_id;
}
```

======
#### Example 1

**Column Configuration**
```php
$datagrid->addColumn('actions', 'action', array(
    'label' => 'Actions',
    'mapping_fields' => array('identity', 'fb_id'),
    'actions' => array(
        'edit' => array(
            'route_name' => '_edit_user',
            'parameters_field_mapping' => array('id' => 'identity'),
            'url_attr' => array(
                'class' => 'btn btn-primary btn-xs',
                'title' => 'datagrid.action.edit'
            ),
            'content' => '<span class="glyphicon glyphicon-edit"></span>',
        ),
        'delete' => array(
            'route_name' => '_delete_user',
            'parameters_field_mapping' => array('id' => 'identity'),
            'url_attr' => function($value, $index) {
                return array(
                    'class' => $index,
                    'title' => 'Delete - ' . $value['id']
                );
            },
            'content' => function($value, $index) {
                return 'Delete - ' . $value['id'];
            }
        ),
        'facebook' => array(
            'url_attr' => function($value, $index) {
                return array(
                    'href' => empty($value['fb_id']) ? '#' : 'https://www.facebook.com/' . $value['fb_id'],
                    'target' => '_blank'
                );
            },
            'content' => function($value, $index) {
                return empty($value['fb_id']) ? ' - ' : 'FB profile';
            }
        )
    )
));
```

**Input**
```php
$user = new User();
$user->id = 1;
$user->fb_id = 100000370790515;
```

```
# app/config/routing.yml
_edit_user:
    path:      /user/edit/{id}
    defaults:  { _controller: FSiDemoBundle:User:edit }

_delete_user:
    path:      /user/delete/{id}
    defaults:  { _controller: FSiDemoBundle:User:delete }
```


**Output**
```
<a class="btn btn-primary btn-xs" title="Edit" href="/user/edit/1"><span class="glyphicon glyphicon-edit"></span></a>
<a class="delete" title="Delete - 1" href="/user/delete/1">Delete - 1</a>
<a target="_blank" href="https://www.facebook.com/100000370790515">FB profile</a>
```

======
