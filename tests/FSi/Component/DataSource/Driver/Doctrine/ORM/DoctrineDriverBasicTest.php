<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Driver\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineDriver;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\CoreExtension;
use FSi\Component\DataSource\Tests\Fixtures\DoctrineDriverExtension;
use FSi\Component\DataSource\Tests\Fixtures\FieldExtension;

/**
 * Basic tests for Doctrine driver.
 */
class DoctrineDriverBasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (!class_exists('Doctrine\ORM\EntityManager')) {
            $this->markTestSkipped('Doctrine needed!');
            return;
        }
    }

    /**
     * Provides names of fields.
     *
     * @return array
     */
    public static function fieldNameProvider()
    {
        return [
            ['text'],
            ['number'],
            ['entity'],
            ['date'],
            ['time'],
            ['datetime'],
            ['boolean'],
        ];
    }

    /**
     * Returns mock of EntityManager.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    private function getEntityManagerMock()
    {
        return $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|EntityManager $em
     * @return \PHPUnit_Framework_MockObject_MockObject|\Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilderMock(EntityManager $em)
    {
        $qb = $this->getMock('Doctrine\ORM\QueryBuilder', [], [$em]);

        $em
            ->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($qb))
        ;

        $qb
            ->expects($this->any())
            ->method('select')
            ->will($this->returnValue($qb))
        ;

        $qb
            ->expects($this->any())
            ->method('from')
            ->will($this->returnValue($qb))
        ;

        return $qb;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|EntityManager $em
     * @param \PHPUnit_Framework_MockObject_MockObject|\Doctrine\ORM\QueryBuilder $qb
     */
    private function extendWithRootEntities($em, $qb, array $map = [['entity', true]])
    {
        $returnMap = [];
        foreach ($map as $info) {
            /** @var \PHPUnit_Framework_MockObject_MockObject|\Doctrine\ORM\Mapping\ClassMetadata $metadata */
            $metadata = $this
                ->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                ->disableOriginalConstructor()
                ->getMock();
            $metadata->isIdentifierComposite = $info[1];

            $returnMap[] = [$info[0], $metadata];
        }

        $qb
            ->expects($this->any())
            ->method('getRootEntities')
            ->will($this->returnValue(['entity']))
        ;
        $qb->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($em))
        ;

        $em
            ->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValueMap($returnMap))
        ;
    }

    /**
     * Checks creation.
     */
    public function testCreation()
    {
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        new DoctrineDriver([], $em, 'entity');
        new DoctrineDriver([], $em, $qb);
    }

    /**
     * Checks creation exception.
     */
    public function testCreationException3()
    {
        $this->setExpectedException('FSi\Component\DataSource\Exception\DataSourceException');
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        new DoctrineDriver([new \stdClass()], $em, 'entity');
    }

    /**
     * Checks creation exception.
     */
    public function testCreationException4()
    {
        $this->setExpectedException('FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException');
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        new DoctrineDriver([], $em, null);
    }

    /**
     * Checks basic getResult call.
     */
    public function testGetResult()
    {
        $fields = [];

        for ($x = 0; $x < 6; $x++) {
            $field = $this->getMock('FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField');

            $field
                ->expects($this->atLeastOnce())
                ->method('buildQuery')
            ;

            $fields[] = $field;
        }

        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        $this->extendWithRootEntities($em, $qb);

        $driver = new DoctrineDriver([], $em, 'entity');
        $driver->getResult($fields, 0, 20);
    }

    /**
     * Checks exception when fields aren't proper instances.
     */
    public function testGetResultException1()
    {
        $fields = [$this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface')];

        $em = $this->getEntityManagerMock();
        $qb = $this->getMock('Doctrine\ORM\QueryBuilder', [], [$em]);

        $em
            ->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($qb))
        ;

        $qb
            ->expects($this->any())
            ->method('select')
            ->will($this->returnValue($qb))
        ;

        $driver = new DoctrineDriver([], $em, 'entity');
        $this->setExpectedException('FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException');
        $driver->getResult($fields, 0, 20);
    }

    /**
     * Checks exception when trying to access the query builder not during getResult method.
     */
    public function testGetQueryException()
    {
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);

        $driver = new DoctrineDriver([], $em, 'entity');
        $this->setExpectedException('FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException');
        $driver->getQueryBuilder();
    }

    /**
     * Checks CoreExtension.
     */
    public function testCoreExtension()
    {
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        $driver = new DoctrineDriver([new CoreExtension()], $em, 'entity');

        $this->assertTrue($driver->hasFieldType('text'));
        $this->assertTrue($driver->hasFieldType('number'));
        $this->assertTrue($driver->hasFieldType('entity'));
        $this->assertTrue($driver->hasFieldType('date'));
        $this->assertTrue($driver->hasFieldType('time'));
        $this->assertTrue($driver->hasFieldType('datetime'));
        $this->assertTrue($driver->hasFieldType('boolean'));
        $this->assertFalse($driver->hasFieldType('wrong'));
        $this->assertFalse($driver->hasFieldType(null));

        $driver->getFieldType('text');
        $this->setExpectedException('FSi\Component\DataSource\Exception\DataSourceException');
        $driver->getFieldType('wrong');
    }

    /**
     * Checks all fields of CoreExtension.
     *
     * @dataProvider fieldNameProvider
     */
    public function testCoreFields($type)
    {
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        $this->extendWithRootEntities($em, $qb);

        $driver = new DoctrineDriver([new CoreExtension()], $em, 'entity');
        $this->assertTrue($driver->hasFieldType($type));
        $field = $driver->getFieldType($type);
        $this->assertTrue($field instanceof \FSi\Component\DataSource\Field\FieldTypeInterface);
        $this->assertTrue($field instanceof \FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineFieldInterface);

        $this->assertTrue($field->getOptionsResolver()->isDefined('field'));

        $comparisons = $field->getAvailableComparisons();
        $this->assertTrue(count($comparisons) > 0);

        foreach ($comparisons as $cmp) {
            $field = $driver->getFieldType($type);
            $field->setName('name');
            $field->setComparison($cmp);
            $field->setOptions([]);
        }

        $this->assertEquals($field->getOption('field'), $field->getName());

        $this->setExpectedException('FSi\Component\DataSource\Exception\FieldException');
        $field = $driver->getFieldType($type);
        $field->setComparison('wrong');
    }

    /**
     * Checks extensions calls.
     */
    public function testExtensionsCalls()
    {
        $em = $this->getEntityManagerMock();
        $qb = $this->getQueryBuilderMock($em);
        $this->extendWithRootEntities($em, $qb);

        $extension = new DoctrineDriverExtension();
        $driver = new DoctrineDriver([], $em, 'entity');
        $driver->addExtension($extension);

        $driver->getResult([], 0, 20);
        $this->assertEquals(['preGetResult', 'postGetResult'], $extension->getCalls());

        $this->setExpectedException('FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException');
        $driver->getQueryBuilder();
    }

    /**
     * Checks fields extensions calls.
     */
    public function testFieldsExtensionsCalls()
    {
        $extension = new FieldExtension();
        $parameter = [];

        foreach ([new Field\Text(), new Field\Number(), new Field\Date(), new Field\Time(), new Field\DateTime(), new Field\Entity()] as $field) {
            $field->addExtension($extension);

            $field->bindParameter([]);
            $this->assertEquals(['preBindParameter', 'postBindParameter'], $extension->getCalls());
            $extension->resetCalls();

            $field->getParameter($parameter);
            $this->assertEquals(['postGetParameter'], $extension->getCalls());
            $extension->resetCalls();

            $field->createView([]);
            $this->assertEquals(['postBuildView'], $extension->getCalls());
            $extension->resetCalls();
        }
    }
}
