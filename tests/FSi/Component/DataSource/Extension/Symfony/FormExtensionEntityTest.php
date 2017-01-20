<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Extension\Symfony;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Event\FieldEvent;
use FSi\Component\DataSource\Extension\Symfony\Form\Driver\DriverExtension;
use FSi\Component\DataSource\Field\FieldAbstractExtension;
use FSi\Component\DataSource\Tests\Fixtures\TestManagerRegistry;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\Form;
use Symfony\Component\Security;

/**
 * Tests for Symfony Form Extension.
 */
class FormExtensionEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (!class_exists('Symfony\Component\Form\Form')) {
            $this->markTestSkipped('Symfony Form needed!');
        }

        if (!class_exists('Doctrine\ORM\EntityManager')) {
            $this->markTestSkipped('Doctrine ORM needed!');
        }
    }

    /**
     * Returns mock of FormFactory.
     *
     * @return object
     */
    private function getFormFactory()
    {
        //The connection configuration.
        $dbParams = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/../../Fixtures'), true, null, null, false);
        $em = EntityManager::create($dbParams, $config);
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = array(
            $em->getClassMetadata('FSi\Component\DataSource\Tests\Fixtures\News'),
        );
        $tool->createSchema($classes);

        $typeFactory = new Form\ResolvedFormTypeFactory();
        $registry = new Form\FormRegistry(
            array(
                new Form\Extension\Core\CoreExtension(),
                new Form\Extension\Csrf\CsrfExtension(new Security\Csrf\CsrfTokenManager()),
                new DoctrineOrmExtension(new TestManagerRegistry($em)),
            ),
            $typeFactory
        );
        return new Form\FormFactory($registry, $typeFactory);
    }

    /**
     * Checks entity field.
     */
    public function testEntityField()
    {
        $type = 'entity';
        $formFactory = $this->getFormFactory();
        $extension = new DriverExtension($formFactory);
        $field = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

        $datasource
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('datasource'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('name'))
        ;

        $field
            ->expects($this->any())
            ->method('getDataSource')
            ->will($this->returnValue($datasource))
        ;

        $field
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name'))
        ;

        $field
            ->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('entity'))
        ;

        $field
            ->expects($this->any())
            ->method('hasOption')
            ->will($this->returnCallback(function () {
                $args = func_get_args();
                if (array_shift($args) == 'form_options') {
                    return true;
                }
                return false;
            }))
        ;

        $field
            ->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function () {
                switch (func_get_arg(0)) {
                    case 'form_filter':
                        return true;

                    case 'form_options':
                        return array(
                            'class' => 'FSi\Component\DataSource\Tests\Fixtures\News',
                        );
                }
            }))
        ;

        $extensions = $extension->getFieldTypeExtensions($type);

        $parameters = array('datasource' => array(DataSourceInterface::PARAMETER_FIELDS => array('name' => 'value')));
        //Form extension will remove 'name' => 'value' since this is not valid entity id (since we have no entities at all).
        $parameters2 = array('datasource' => array(DataSourceInterface::PARAMETER_FIELDS => array()));
        $args = new FieldEvent\ParameterEventArgs($field, $parameters);
        foreach ($extensions as $ext) {
            $this->assertTrue($ext instanceof FieldAbstractExtension);
            $ext->preBindParameter($args);
        }
        $parameters = $args->getParameter();
        $this->assertEquals($parameters2, $parameters);
        $fieldView = $this->getMock('FSi\Component\DataSource\Field\FieldViewInterface', array(), array($field));

        $fieldView
            ->expects($this->atLeastOnce())
            ->method('setAttribute')
        ;

        $args = new FieldEvent\ViewEventArgs($field, $fieldView);
        foreach ($extensions as $ext) {
            $ext->postBuildView($args);
        }
    }
}
