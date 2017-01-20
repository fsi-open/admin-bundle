# Templating

Default DataGrid block used to render each parts of DataGrid are very simple, but in most cases you will need to overwrite them.
This can be easily done with theming mechanism.
Theme is nothing else than a twig template that contains specific blocks.

If you want to set theme for your DataGridView Object you need to use special tag ``datagrid_theme``.
example:

```
{% block body %}
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

    {% datagrid_theme datagrid 'FSiDemoBundle::datagrid.html.twig' %}

    <div class="table-border">
        <form action="{{ path('demo_index') }}" method="post">
        {{ datagrid_widget(datagrid) }}
        </form>
    </div>
{% endblock %}
```
From now datagrid_widget function will load themes for datagrid parts from FSiDemoBundle::datagrid.html.twig.
Of course you can use ``_self`` value as a theme file.
```
{% block body %}
    {% datagrid_theme datagrid _self %}

    {# ... #}
{% endblock %}
```
From now datagrid_widget function will load themes for datagrid parts from current twig file.

You can pass some additional variables that be will available in context of each template block.

```
{% block body %}
    {% datagrid_theme datagrid _self with {'user': app.user} %}

    {# ... #}
{% endblock %}
```

Thanks to the above code every template block will have ``vars.user`` variable.

Ok so now simple example how to add header above datagrid, you need to know that datagrid
is rendered as a table by default.

```
{% block body %}
    {% datagrid_theme datagrid _self %}

    {% block datagrid %}
        <h2>Custom DataGrid View</h2>
        <table class="table table-hover" id="table-edit-rows">
            <thead>
                {{ datagrid_header_widget(datagrid) }}
            </thead>
            <tbody>
                {{ datagrid_rowset_widget(datagrid) }}
            </tbody>
        </table>
    {% endblock %}

    <div class="table-border">
        <form action="{{ path('demo_index') }}" method="post">
        {{ datagrid_widget(datagrid) }}
        </form>
    </div>
{% endblock %}
```

As you can see it's quite simple. To reder ``datagrid_widget`` twig function use block with name ``datagrid``
that is located in theme file.

There are few more twig functions used to render datagrid.

``datagrid_widget``
* ``{grid_name}_datagrid``
* ``datagrid``

``datagrid_header_widget``
* ``datagrid_{grid_name}_header``
* ``datagrid_header``

``datagrid_column_header_widget``
* ``datagrid_{grid_name}_column_name_{column_name}_header``
* ``datagrid_{grid_name}_column_type_{column_type}_header``
* ``datagrid_column_name_{column_name}_header``
* ``datagrid_column_type_{column_type}_header``
* ``datagrid_{grid_name}_column_header``
* ``datagrid_column_header``

``datagrid_rowset_widget``
* ``datagrid_{grid_name}_rowset``
* ``datagrid_rowset``

``datagrid_column_cell_widget``
* ``datagrid_{grid_name}_column_name_{column_name}_cell``
* ``datagrid_{grid_name}_column_type_{column_type}_cell``
* ``datagrid_column_name_{column_name}_cell``
* ``datagrid_column_type_{column_type}_cell``
* ``datagrid_{grid_name}_column_cell``
* ``datagrid_column_cell``

``datagrid_column_cell_form_widget``
* ``datagrid_{grid_name}_column_name_{column_name}_cell_form``
* ``datagrid_{grid_name}_column_type_{column_type}_cell_form``
* ``datagrid_column_name_{column_name}_cell_form``
* ``datagrid_column_type_{column_type}_cell_form``
* ``datagrid_{grid_name}_column_cell_form``
* ``datagrid_column_cell_form``

``datagrid_column_type_action_cell_action_widget``
* ``datagrid_{grid_name}_column_type_action_cell_action_{action_name}``
* ``datagrid_column_type_action_cell_action_{action_name}``
* ``datagrid_{grid_name}_column_type_action_cell_action``
* ``datagrid_column_type_action_cell_action``

As you can see you can even create named block that will be used only to render datagrid/column that match name/type
used in block name.
