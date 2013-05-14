<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractContext implements ContextInterface
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @param string $template
     */
    public function __construct($template = null)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return isset($this->template);
    }
}