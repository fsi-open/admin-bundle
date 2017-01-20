# Entity

###Available Options
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
            <td>Options</td>
            <td>
                <ul>
                    <li>auto_alias</li>
                    <li>clause</li>
                    <li>field (optional) </li>

                </ul>
            </td>
            <td>
                <ul>
                    <li>bool</li>
                    <li>where|having</li>
                    <li>string|null</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><code>true</code></li>
                    <li><code>'where'</code></li>
                    <li><code>$field->getName()</code></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>Field Extension Options</td>
            <td>
                <ul>
                    <li>default_sort</li>
                    <li>sortable</li>
                    <li>default_sort_priority (optional)</li>
                </ul>
            </td>
            <td>
                <ul>
                    <li>null|asc|desc</li>
                    <li>bool</li>
                    <li>integer</li>


                </ul>
            </td>
            <td>
                <ul>
                    <li><code>null</code></li>
                    <li><code>true</code></li>
                    <li><code>empty</code></li>

                </ul>
            </td>
        </tr>
        <tr>
            <td>Form Extension</td>
            <td>
                <ul>
                    <li>form_filter</li>
                    <li>form_options</li>
                    <li>form_from_options</li>
                    <li>form_to_options</li>
                    <li>form_type (optional) </li>
                    <li>form_order (optional) </li>

                </ul>
            </td>
            <td>
                <ul>
                    <li>bool</li>
                    <li>array</li>
                    <li>array</li>
                    <li>array</li>
                    <li>integer</li>
                    <li>string</li>
                </td>
            </td>
            <td>
                <ul>
                    <li><code>true</code></li>
                    <li><code>array()</code></li>
                    <li><code>array()</code></li>
                    <li><code>array()</code></li>
                    <li><code>empty</code></li>
                    <li><code>empty</code></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>

####Available Comparison Types:

* eq
* memberof
* in
* isNull

### Usage example

```php
$datasource->addField('user','entity','in')
```

Result as DQL:
```dql
SELECT g FROM FSiDemoBundle:Group g WHERE g.user IN(:user)
```
