# Home page

By default the admin panel home page (index page) is just a blank page with
navigation bar, but without any content.

You can customize it to fit your needs.

## Override the template file

If you don't need any extra logic, you can just override the home page template
file with your own.

```
#app/config/config.yml

fsi_admin:
    templates:
        index_page: YourAdminBundle:Admin:index.html.twig
```

It is a good idea to let your custom home page template file extend the FSi
Admin Bundle base template file.

```
# YourAdminBundle/Resources/views/Admin/index.html.twig

{% extends admin_templates_base %}

{% block content %}
{# Your content goes here... #}
{% endblock %}
```

## Override the route

If you want to have your own controller with custom logic, you can override the
home page route (just like any other route in Symfony 2).

```
# app/config/routing.yml

fsi_admin:
    path: /
    defaults:
        _controller: YourAdminBundle:Admin:index
```
