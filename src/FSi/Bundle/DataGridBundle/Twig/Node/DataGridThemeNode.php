<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Twig\Node;

class DataGridThemeNode extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $dataGrid, \Twig_NodeInterface $theme, \Twig_Node_Expression_Array $vars, $lineno, $tag = null)
    {
        parent::__construct(array('datagrid' => $dataGrid, 'theme' => $theme, 'vars' => $vars), array(), $lineno, $tag);
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