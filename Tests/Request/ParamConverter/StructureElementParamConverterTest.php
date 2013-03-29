<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Request\ParamConverter\StructureElementParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class StructureElementParamConverterTest extends \PHPUnit_Framework_TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = $this->getMock('FSi\Bundle\AdminBundle\Structure\GroupManager');
    }

    public function testSupports()
    {
        $converter = new StructureElementParamConverter($this->manager);

        $config = $this->createConfiguration('FSi\Bundle\AdminBundle\Structure\ElementInterface');
        $this->assertTrue($converter->supports($config));

        $config = $this->createConfiguration(__CLASS__);
        $this->assertFalse($converter->supports($config));

        $config = $this->createConfiguration();
        $this->assertFalse($converter->supports($config));
    }

    public function testApply()
    {
        $request = new Request(array(), array(), array('id' => '1'));
        $config = $this->createConfiguration('FSi\Bundle\AdminBundle\Structure\ElementInterface', 'id');

        $self = $this;
        $this->manager->expects($this->any())
            ->method('findElementById')
            ->with('1')
            ->will($this->returnCallback(function() use($self){
                return $self->getMock('FSi\Bundle\AdminBundle\Structure\ElementInterface');
            }));

        $converter = new StructureElementParamConverter($this->manager);
        $converter->apply($request, $config);

        $this->assertInstanceOf('FSi\Bundle\AdminBundle\Structure\ElementInterface', $request->attributes->get('id'));
    }

    public function createConfiguration($class = null, $name = null)
    {
        $config = $this->getMock(
            'Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface', array(
            'getClass', 'getAliasName', 'getOptions', 'getName', 'allowArray'
        ));

        if ($name !== null) {
            $config->expects($this->any())
                ->method('getName')
                ->will($this->returnValue($name));
        }

        if ($class !== null) {
            $config->expects($this->any())
                ->method('getClass')
                ->will($this->returnValue($class));
        }

        return $config;
    }
}