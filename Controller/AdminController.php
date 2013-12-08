<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $indexActionTemplate;

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param string $indexActionTemplate
     */
    function __construct(EngineInterface $templating, $indexActionTemplate)
    {
        $this->templating = $templating;
        $this->indexActionTemplate = $indexActionTemplate;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->templating->renderResponse($this->indexActionTemplate);
    }
}
