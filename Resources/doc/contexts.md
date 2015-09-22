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
Context is responsible for handling request for admin element through the `handleRequest(Request $request)` method.
It takes a request instance and should return either a response (for example a redirect) or a null.
If a null is returned, then AdminBundle internally renders template from getTemplateName() method with data from
the getData() method.

All internal contexts handle data by iterating over several [HandlerInterface's](Admin/Context/Request/HandlerInterface.php)
to support datagrid, datasource and form handling.

You can register custom contexts by creating services tagged as in the example below. 
```xml
<service class="ExampleBundle\AdminContext\MyContext">
    <tag name="admin.context" priority="5"/>
</service>
```
If the custom contexts are to be invoked before defaults, they need to have priority set to higher than 0.

## Context manager
Context manager holds all contexts. Most important method is `createContext($route, Element $element)`.
It iterates over all contexts checking if context supports route and admin element through
`supports($route, Element $element)` method. Then it sets admin element on supporting contexts and returns this context.
