# Contexts, Context builders and manager
```
+-------------------------------+      +---------------------------------------+
|ContextManager                 +<>----+ContextBuilderInterface                |
+-------------------------------+      +---------------------------------------+
|__construct($builders)         |      |supports($route, $element):bool        |
|createContext($route, $element)|      |buildContext($element):ContextInterface|
|addContextBuilder($builder)    |      +------+--------------------------------+
+-------------------------------+             |                    
                                              |                    
                                       +------+----------------+   
                                       |ContextInterface       |   
                                       +-----------------------+   
                                       |handleRequest($request)|   
                                       |hasTemplateName()      |   
                                       |getTemplateName()      |   
                                       |getData()              |   
                                       +-----------------------+   
```

## Context
Context is responsible for handling request for admin element through the `handleRequest(Request $request)` method. It takes a request instance and should return either a response (for example a redirect) or a null. If a null is returned, then AdminBundle internally renders template from getTemplateName() method with data from the getData() method.

All internal contexts handle data by iterating over several [HandlerInterface's](Admin/Context/Request/HandlerInterface.php) to support datagrid, datasource and form handling.


## Context builder
Context first fires the `supports($route, Element $element)` to check whether this builder supports supplied route and admin element. If it does, the `buildContext(Element $element)` method return an instance of the context.

You can register custom builders by creating services tagged as in the example below. 
```xml
<service class="ExampleBundle\AdminContext\MyContextBuilder">
    <tag name="admin.context.builder" priority="5"/>
</service>
```
If the custom context builders are to be invoked before defaults, they need to have priority set to higher than 0.

## Context manager
Context manager holds all context builders. Most important method is `createContext($route, Element $element)`. It iterates over all builders checking if builder supports route and admin element. Then it delegates creation of context to first builder that supports.
