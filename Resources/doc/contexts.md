# Contexts, Context builders and manager
```
+-------------------------------+        +--------------------------+
|ContextManager                 +<>------+ContextBuilderInterface   |
+-------------------------------+        +--------------------------+
|__construct($builders)         |        |supports($route, $element)|
|createContext($route, $element)|        |buildContext($element)    |
|addContextBuilder($builder)    |        +------+-------------------+
+-------------------------------+               |                    
                                                |<<creates>>         
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
Context is responsible for handling request of admin element through `handleRequest(Request $request)` method. If take request instance and should return response (for example redirect) or null. If null is returned then Admin bundle internally render template from `getTemplateName()` method with data from `getData()` method.

All internal contexts handle data by iterating over several [HandlerInterface's](Admin/Context/Request/HandlerInterface.php) to support datagrid, datasource and form handling.

## Context builder
Context builder create context instance. First `supports($route, Element $element)` method is invoked to check that this builder supports route and admin element. Then `buildContext(Element $element)` method should produce context instance.

You can register custom builder by creating service tagged as in following example. Custom context builders shoud have higher priority than 0 to be invoked before defaults.
```xml
<service class="ExampleBundle\AdminContext\MyContextBuilder">
    <tag name="admin.context.builder" priority="5"/>
</service>
```

## Context manager
Context manager holds all context builders. Most important method is `createContext($route, Element $element)`. It iterates over all builders checking if builder supports route and admin element. Then it delegates creation of context to first builder that supports.
