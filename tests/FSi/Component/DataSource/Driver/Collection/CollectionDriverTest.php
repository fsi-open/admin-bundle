<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Driver\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use FSi\Component\DataSource\DataSourceFactory;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Driver\Collection\CollectionFactory;
use FSi\Component\DataSource\Driver\Collection\Extension\Core\CoreExtension;
use FSi\Component\DataSource\Driver\DriverFactoryManager;
use FSi\Component\DataSource\Extension\Core;
use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use FSi\Component\DataSource\Extension\Core\Pagination\PaginationExtension;
use FSi\Component\DataSource\Extension\Symfony;
use FSi\Component\DataSource\Tests\Fixtures\Category;
use FSi\Component\DataSource\Tests\Fixtures\Group;
use FSi\Component\DataSource\Tests\Fixtures\News;
use FSi\Component\DataSource\Tests\Fixtures\TestManagerRegistry;
use Symfony\Component\Form;

/**
 * Tests for Doctrine driver.
 */
class CollectionDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (!class_exists('Doctrine\ORM\EntityManager')) {
            $this->markTestSkipped('Doctrine needed!');
        }

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
            $em->getClassMetadata('FSi\Component\DataSource\Tests\Fixtures\Category'),
            $em->getClassMetadata('FSi\Component\DataSource\Tests\Fixtures\Group'),
        );
        $tool->createSchema($classes);
        $this->load($em);
        $this->em = $em;
    }

    /**
     * Test number field when comparing with 0 value.
     */
    public function testComparingWithZero()
    {
        $datasourceFactory = $this->getDataSourceFactory();
        $driverOptions = array(
            'collection' => $this->em->getRepository('FSi\Component\DataSource\Tests\Fixtures\News')->findAll(),
        );

        $datasource = $datasourceFactory
            ->createDataSource('collection', $driverOptions, 'datasource')
            ->addField('id', 'number', 'eq');

        $parameters = array(
            $datasource->getName() => array(
                DataSourceInterface::PARAMETER_FIELDS => array(
                    'id' => '0',
                ),
            ),
        );
        $datasource->bindParameters($parameters);
        $result = $datasource->getResult();
        $this->assertEquals(0, count($result));
    }

    /**
     * General test for DataSource wtih DoctrineDriver in basic configuration.
     */
    public function testGeneral()
    {
        $datasourceFactory = $this->getDataSourceFactory();

        $driverFactory = $this->getCollectionFactory();
        $driver = $driverFactory->createDriver();

        $datasources = array();

        $driverOptions = array(
            'collection' => $this->em->getRepository('FSi\Component\DataSource\Tests\Fixtures\News')->findAll(),
        );

        $datasources[] = $datasourceFactory->createDataSource('collection', $driverOptions, 'datasource');

        $qb = $this->em
            ->createQueryBuilder()
            ->select('n')
            ->from('FSi\Component\DataSource\Tests\Fixtures\News', 'n')
        ;

        $driverOptions = array(
            'collection' => $qb->getQuery()->execute()
        );

        $datasources[] = $datasourceFactory->createDataSource('collection', $driverOptions, 'datasource2');

        foreach ($datasources as $datasource) {
            $datasource
                ->addField('title', 'text', 'contains')
                ->addField('author', 'text', 'contains')
                ->addField('created', 'datetime', 'between', array(
                    'field' => 'create_date',
                ))
            ;

            $result1 = $datasource->getResult();
            $this->assertEquals(100, count($result1));
            $view1 = $datasource->createView();

            //Checking if result cache works.
            $this->assertSame($result1, $datasource->getResult());

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'author' => 'domain1.com',
                    ),
                ),
            );
            $datasource->bindParameters($parameters);
            $result2 = $datasource->getResult();

            //Checking cache.
            $this->assertSame($result2, $datasource->getResult());

            $this->assertEquals(50, count($result2));
            $this->assertNotSame($result1, $result2);
            unset($result1);
            unset($result2);

            $this->assertEquals($parameters, $datasource->getParameters());

            $datasource->setMaxResults(20);
            $parameters = array(
                $datasource->getName() => array(
                    PaginationExtension::PARAMETER_PAGE => 1,
                ),
            );

            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(100, count($result));
            $i = 0;
            foreach ($result as $item) {
                $i++;
            }
            $this->assertEquals(20, $i);

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'author' => 'domain1.com',
                        'title' => 'title3',
                        'created' => array(
                            'from' => new \DateTime(date("Y:m:d H:i:s", 35 * 24 * 60 * 60)),
                        ),
                    ),
                ),
            );
            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(2, count($result));

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'author' => 'author3@domain2.com',
                    ),
                )
            );
            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(1, count($result));

            //Checking sorting.
            $parameters = array(
                $datasource->getName() => array(
                    OrderingExtension::PARAMETER_SORT => array(
                        'title' => 'desc'
                    ),
                ),
            );

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals('title99', $news->getTitle());
                break;
            }

            //Checking sorting.
            $parameters = array(
                $datasource->getName() => array(
                    OrderingExtension::PARAMETER_SORT => array(
                        'author' => 'asc',
                        'title' => 'desc',
                    ),
                ),
            );

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals('author0@domain1.com', $news->getAuthor());
                break;
            }

            //Test for clearing fields.
            $datasource->clearFields();
            $datasource->setMaxResults(null);
            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'author' => 'domain1.com',
                    ),
                ),
            );

            //Since there are no fields now, we should have all of entities.
            $datasource->bindParameters($parameters);
            $result = $datasource->getResult();
            $this->assertEquals(100, count($result));

            //Test boolean field
            $datasource
                ->addField('active', 'boolean', 'eq')
            ;
            $datasource->setMaxResults(null);
            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'active' => 1,
                    ),
                )
            );

            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(50, count($result));

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'active' => 0,
                    ),
                )
            );

            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(50, count($result));

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'active' => true,
                    ),
                )
            );

            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(50, count($result));

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'active' => false,
                    ),
                )
            );

            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(50, count($result));

            $parameters = array(
                $datasource->getName() => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        'active' => null,
                    ),
                )
            );

            $datasource->bindParameters($parameters);
            $view = $datasource->createView();
            $result = $datasource->getResult();
            $this->assertEquals(100, count($result));

            $parameters = array(
                $datasource->getName() => array(
                    OrderingExtension::PARAMETER_SORT => array(
                        'active' => 'desc'
                    ),
                ),
            );

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals(true, $news->isActive());
                break;
            }

            $parameters = array(
                $datasource->getName() => array(
                    OrderingExtension::PARAMETER_SORT => array(
                        'active' => 'asc'
                    ),
                ),
            );

            $datasource->bindParameters($parameters);
            foreach ($datasource->getResult() as $news) {
                $this->assertEquals(false, $news->isActive());
                break;
            }
        }
    }

    public function testExceptions()
    {
        $datasourceFactory = $this->getDataSourceFactory();

        $driverFactory = $this->getCollectionFactory();
        $driver = $driverFactory->createDriver();

        $driverOptions = array(
            'collection' => $this->em->getRepository('FSi\Component\DataSource\Tests\Fixtures\News')->findAll(),
        );

        $datasource = $datasourceFactory->createDataSource('collection', $driverOptions, 'datasource');
        $field = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');

        $field
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('example'))
        ;

        $datasource->addField($field);

        $this->setExpectedException('FSi\Component\DataSource\Driver\Collection\Exception\CollectionDriverException');
        $result1 = $datasource->getResult();
    }

    public function testExceptions2()
    {
        $datasourceFactory = $this->getDataSourceFactory();

        $driverFactory = $this->getCollectionFactory();
        $driver = $driverFactory->createDriver();

        $this->setExpectedException('FSi\Component\DataSource\Driver\Collection\Exception\CollectionDriverException');
        $driver->getCriteria();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($this->em);
    }

    /**
     * Return configured DoctrinFactory.
     *
     * @return DoctrineFactory.
     */
    private function getCollectionFactory()
    {
        $extensions = array(
            new CoreExtension(),
        );

        return new CollectionFactory($extensions);
    }

    /**
     * Return configured DataSourceFactory.
     *
     * @return \FSi\Component\DataSource\DataSourceFactory
     */
    private function getDataSourceFactory()
    {
        $driverFactoryManager = new DriverFactoryManager(array(
            $this->getCollectionFactory()
        ));

        $extensions = array(
            new Symfony\Core\CoreExtension(),
            new Core\Pagination\PaginationExtension(),
            new OrderingExtension(),
        );

        return new DataSourceFactory($driverFactoryManager, $extensions);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    private function load(EntityManager $em)
    {
        //Injects 5 categories.
        $categories = array();
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName('category'.$i);
            $em->persist($category);
            $categories[] = $category;
        }

        //Injects 4 groups.
        $groups = array();
        for ($i = 0; $i < 4; $i++) {
            $group = new Group();
            $group->setName('group'.$i);
            $em->persist($group);
            $groups[] = $group;
        }

        //Injects 100 newses.
        for ($i = 0; $i < 100; $i++) {
            $news = new News();
            $news->setTitle('title'.$i);

            //Half of entities will have different author and content.
            if ($i % 2 == 0) {
                $news->setAuthor('author'.$i.'@domain1.com');
                $news->setShortContent('Lorem ipsum.');
                $news->setContent('Content lorem ipsum.');
            } else {
                $news->setAuthor('author'.$i.'@domain2.com');
                $news->setShortContent('Dolor sit amet.');
                $news->setContent('Content dolor sit amet.');
                $news->setActive();
            }

            //Each entity has different date of creation and one of four hours of creation.
            $createDate = new \DateTime(date("Y:m:d H:i:s", $i * 24 * 60 * 60));
            $createTime = new \DateTime(date("H:i:s", (($i % 4) + 1 ) * 60 * 60));

            $news->setCreateDate($createDate);
            $news->setCreateTime($createTime);

            $news->setCategory($categories[$i % 5]);
            $news->getGroups()->add($groups[$i % 4]);

            $em->persist($news);
        }

        $em->flush();
    }
}
