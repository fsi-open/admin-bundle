<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Twig\Extension;

/**
 * @author Bartosz Bialek <bartosz.bialek@fsi.pl>
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminExtension extends \Twig_Extension
{
    /**
     * Array of templates that will be registered as twig globals.
     * @var array
     */
    protected $templates;

    /**
     * @param array $templates
     */
    function __construct($templates = array())
    {
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return $this->templates;
    }

    /**
     * {@inheritdoc}
     */
    function getName()
    {
        return 'admin';
    }
}
