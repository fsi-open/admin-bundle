# Templating

Default DataSource blocks used to render each parts of DataSource are very simple, so in many cases you will need to overwrite
them. This can be easily done with theming mechanism. Theme is nothing else than a twig template that contains blocks with
specific names.

If you want to set theme for your DataSourceView object you need to use special twig tag ``datagrid_theme``.
example:
```
{% block body %}
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

    {% datasource_theme datasource 'FSiDemoBundle::datasource.html.twig' %}

    <form action="{{ path('demo_index') }}" method="post">
        {{ datasource_filter_widget(datasource) }}
    </form>
{% endblock %}
```

From now datasource_filter_widget function will load themes for datasource parts from FSiDemoBundle::datasource.html.twig.
Of course you can use ``_self`` value as a theme file.

```
{% block body %}
    {% datasource_theme datasource _self %}

    {# ... #}
{% endblock %}
```

From now datasource_filter_widget, datasource_pagination_widget and datasource_sort_widget function will load themes for
datasource parts from current twig file.

Ok so now simple example how to add header above datasource.

```
{% block body %}
{# src/FSi/Bundle/DemoBundle/Resources/views/Default/index.html.twig #}

    {% datasource_theme datasource _self %}

    {% block datasource_filter %}
        <h1>DataSource Filters</h1>
        {% spaceless %}
            {% for field in datasource.fields %}
                {{ datasource_field_widget(field, vars) }}
            {% endfor %}
        {% endspaceless %}
    {% endblock %}

    <form action="{{ path('demo_index') }}" method="post">
        {{ datasource_filter_widget(datasource) }}
    </form>
{% endblock %}
```

As you can see it's quite simple. To render ``datasource_filter_widget`` twig function use block with name ``datasource_filter``
that is located in theme file.

There are few more twig functions used to render datagrid.

``datasource_filter_widget``
* ``datasource_{source_name}_filter``
* ``datasource_filter``

``datasource_field_widget``
* ``datasource_{source_name}_field_name_{field_name}``
* ``datasource_{source_name}_field_type_{field_type}``
* ``datasource_field_name_{field_name}``
* ``datasource_field_type_{field_type}``
* ``datasource_{source_name}_field``
* ``datasource_field``

``datasource_sort_widget``
* ``datasource_{source_name}_sort``
* ``datasource_sort``

``datasource_pagination_widget``
* ``datasource_{source_name}_pagination``
* ``datasource_pagination``

As you can see you can even create named block that will be used only to render filter/pagination/sort button that match
datasource name/type used in block name.