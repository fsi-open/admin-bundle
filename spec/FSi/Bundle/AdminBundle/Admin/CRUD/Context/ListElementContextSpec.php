<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class ListElementContextSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Component\DataSource\DataSource $datasource
     * @param \FSi\Component\DataGrid\DataGrid $datagrid
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     */
    function let($element, $datasource, $datagrid, $handler)
    {
        $this->beConstructedWith(array($handler));
        $element->createDataGrid()->willReturn($datagrid);
        $element->createDataSource()->willReturn($datasource);
        $this->setElement($element);
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('datagrid_view');
        $this->getData()->shouldHaveKeyInArray('datasource_view');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     */
    function it_has_template($element)
    {
        $element->hasOption('template_list')->willReturn(true);
        $element->getOption('template_list')->willReturn('this_is_list_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_list_template.html.twig');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_handle_request_with_request_handlers($handler, $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface $handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_return_response_from_handler($handler, $request)
    {
        $handler->handleRequest(Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent'), $request)
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
