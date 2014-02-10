<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContextSpec extends ObjectBehavior
{
    protected $data;

    function let(HandlerInterface $handler, CRUDElement $element, Form $form)
    {
        $this->data = new \stdClass();
        $this->beConstructedWith(array($handler));
        $this->setElement($element);
        $element->createForm($this->data)->willReturn($form);
        $this->setEntity($this->data);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data(CRUDElement $element, DataIndexerInterface $indexer)
    {
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getIndex($this->data)->willReturn(1);
        $element->getOption('crud_edit_title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
        $this->getData()->shouldHaveKeyInArray('id');
        $this->getData()->shouldHaveKeyInArray('title');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_edit')->willReturn(true);
        $element->getOption('template_crud_edit')->willReturn('this_is_edit_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_edit_template.html.twig');
    }

    function it_handle_request_with_request_handlers(HandlerInterface $handler, Request $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request
    ) {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return array(
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        );
    }
}
