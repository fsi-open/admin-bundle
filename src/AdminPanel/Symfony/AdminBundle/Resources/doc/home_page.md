# Home page

By default the admin panel home page (index page) is just a blank page with
navigation bar, but you can customize it to fit your needs.

## Override the template file

If you don't need any extra logic, you can just override the home page template
file with your own.

```yaml
#app/config/config.yml

fsi_admin:
    templates:
        index_page: @YourAdmin/Admin/index.html.twig
```

It is a good idea to have your custom template file extend the FSi Admin Bundle base template file.

```twig
# YourAdminBundle/Resources/views/Admin/index.html.twig

{% extends admin_templates_base %}

{% block content %}
{# Your content goes here... #}
{% endblock %}
```

## Override the route

If you want to have your own controller with custom logic, you can override the
home page route (just like any other route in Symfony 2).

```yaml
# app/config/routing.yml

fsi_admin:
    path: /
    defaults:
        _controller: YourAdminBundle:Admin:index
```

[Back to index](index.md)
