<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\Twig\Node;

class DataSourceRouteNode extends \Twig_Node
{
    public function __construct(\Twig_Node $dataGrid, \Twig_Node $route, \Twig_Node_Expression $additional_parameters, $lineno, $tag = null)
    {
        parent::__construct(array('datasource' => $dataGrid, 'route' => $route, 'additional_parameters' => $additional_parameters), array(), $lineno, $tag);
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