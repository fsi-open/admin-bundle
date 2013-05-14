<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Twig\Extension;

use FSi\Bundle\AdminBundle\FSiAdminBundle;

/**
 * @author Bartosz Bialek <bartosz.bialek@fsi.pl>
 */
class AdminExtension extends \Twig_Extension
{
    protected $baseTemplate;

    /**
     * @param string $baseTemplate
     */
    function __construct($baseTemplate)
    {
        $this->baseTemplate = $baseTemplate;
    }

    public function getGlobals()
    {
        return array(
            'base_template' => $this->baseTemplate,
        );
    }

    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return 'admin';
    }
}
