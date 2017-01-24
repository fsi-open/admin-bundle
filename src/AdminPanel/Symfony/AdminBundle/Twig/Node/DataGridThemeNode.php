<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Twig\Node;

class DataGridThemeNode extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $dataGrid, \Twig_NodeInterface $theme, \Twig_Node_Expression_Array $vars, $lineno, $tag = null)
    {
        parent::__construct(['datagrid' => $dataGrid, 'theme' => $theme, 'vars' => $vars], [], $lineno, $tag);
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
            ->write('$this->env->getExtension(\'datagrid\')->setTheme(')
            ->subcompile($this->getNode('datagrid'))
            ->raw(', ')
            ->subcompile($this->getNode('theme'))
            ->raw(', ')
            ->subcompile($this->getNode('vars'))
            ->raw(");\n");
        ;
    }
}
