<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Twig\Node;

class DataSourceRouteNode extends \Twig_Node
{
    public function __construct(\Twig_Node $dataGrid, \Twig_Node $route, \Twig_Node_Expression $additional_parameters, $lineno, $tag = null)
    {
        parent::__construct(['datasource' => $dataGrid, 'route' => $route, 'additional_parameters' => $additional_parameters], [], $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'datasource\')->setRoute(')
            ->subcompile($this->getNode('datasource'))
            ->raw(', ')
            ->subcompile($this->getNode('route'))
            ->raw(', ')
            ->subcompile($this->getNode('additional_parameters'))
            ->raw(");\n");
        ;
    }
}
