# Home page

In order to customize admin panel home page you can override its route, just
like any route other in Symfony 2.

```
# app/config/routing.yml

fsi_admin:
    path: /
    defaults:
        _controller: YourAdminBundle:Admin:home
```

It is a good idea to let your custom home page template file extend the FSi
Admin Bundle base template file.

```
# YourAdminBundle/Resources/views/Admin/home.html.twig

{% extends admin_templates_base %}

{% block content %}
{# Your content goes here... #}
{% endblock %}
```
