# Contexts, Context builders and manager
```
+-------------------------------+      +---------------------------------------+
|ContextManager                 +<>----+ContextInterface                       |
+-------------------------------+      +---------------------------------------+
|__construct($builders)         |      |supports($route, $element):bool        |
|createContext($route, $element)|      |buildContext($element):ContextInterface|
|addContextBuilder($builder)    |      |handleRequest($request)                |
+-------------------------------+      |hasTemplateName()                      |
                                       |getTemplateName()                      |
                                       |getData()                              |
                                       |ContextInterface                       |
                                       +---------------------------------------+
```

## Context
Contexts are responsible for handling requests for admin elements through the `handleRequest(Request $request)` method.
It takes a request instance and should return either a response (for example a redirect) or a `null`.
If a `null` is returned, then AdminBundle internally renders template from `getTemplateName()` method with data from
the `getData()` method.

All internal contexts handle data by iterating over several [HandlerInterface](/Admin/Context/Request/HandlerInterface.php)
instances to support datagrid, datasource and form handling.

You can register custom contexts by creating services tagged as in the example below.
```xml
<service class="ExampleBundle\AdminContext\MyContext">
    <tag name="admin.context" priority="5"/>
</service>
```
If the custom contexts are to be invoked before defaults, they need to have priority set to higher than 0.

## Context manager
Context manager holds all contexts. The most important method is `createContext($route, Element $element)` -
it iterates over all contexts checking if they support the route and admin element (via `supports($route, Element $element)` method).
Then it sets admin element on supporting context and returns it.
