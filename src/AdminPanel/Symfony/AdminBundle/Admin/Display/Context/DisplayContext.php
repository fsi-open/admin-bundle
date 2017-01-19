<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Display\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextAbstract;
use AdminPanel\Symfony\AdminBundle\Admin\Display;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Event\DisplayEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DisplayContext extends ContextAbstract
{
    /**
     * @var Display\Element
     */
    protected $element;

    /**
     * @var \AdminPanel\Symfony\AdminBundle\Display\Display
     */
    private $display;

    /**
     * @return boolean
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template');
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            'display' => $this->display->createView(),
            'element' => $this->element,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setElement(Element $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent(Request $request)
    {
        $this->display = $this->element->createDisplay($this->getObject($request));

        return new DisplayEvent($this->element, $request, $this->display);
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_display';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof Display\Element;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getObject(Request $request)
    {
        $id = $request->get('id', null);

        $object = $this->element->getDataIndexer()->getData($id);
        if (!$object) {
            throw new NotFoundHttpException(sprintf("Can't find object with id %s", $id));
        }

        return $object;
    }
}
