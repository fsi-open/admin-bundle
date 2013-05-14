<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext;

use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractContextBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ElementInterface
     */
    abstract public function getElement();

    /**
     * @param ElementInterface $element
     * @return mixed
     */
    abstract public function getContextBuilder(ElementInterface $element);

    public function testSupportElement()
    {
        $this->assertTrue($this->getContextBuilder($this->getElement())->supports($this->getElement()));
    }
}