<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Behat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use FSi\Behat\Fixtures\DemoBundle\Entity\News;
use Symfony\Component\HttpKernel\KernelInterface;

class DataContext extends BehatContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function createDatabase()
    {
        $this->deleteDatabaseIfExist();
        $metadata = $this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->getDoctrine()->getManager());
        $tool->createSchema($metadata);
    }

    /**
     * @AfterScenario
     */
    public function deleteDatabaseIfExist()
    {
        $dbFilePath = $this->kernel->getRootDir() . '/data.sqlite';

        if (file_exists($dbFilePath)) {
            unlink($dbFilePath);
        }
    }

    /**
     * @Given /^following news exist in database$/
     */
    public function followingNewsExistInDatabase(TableNode $table)
    {
        foreach ($table->getHash() as $newsNode) {
            $news = $this->getEntityRepository('FSi\Behat\Fixtures\DemoBundle\Entity\News')->findOneByTitle($newsNode['Title']);
            if (!isset($news)) {
                $news = new News();
            }

            $news->setTitle($newsNode['Title']);
            $news->setCreatedAt(new \DateTime($newsNode['Created at']));
            $news->setVisible($newsNode['Visible'] === 'true');
            $news->setCreatorEmail($newsNode['Created by']);

            $this->getDoctrine()->getManager()->persist($news);
            $this->getDoctrine()->getManager()->flush();
        }
    }


    /**
     * @Given /^there are (\d+) news in database$/
     */
    public function thereAreNewsInDatabase($newsCount)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\Behat\Fixtures\DemoBundle\Entity\News', $newsCount, array(
            'creatorEmail' => function() use ($generator) { return $generator->email(); }
        ));
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\Behat\Fixtures\DemoBundle\Entity\News')->findAll()))->toBe($newsCount);
    }

    /**
     * @param string $name
     * @return \Doctrine\Orm\EntityRepository
     */
    protected function getEntityRepository($name)
    {
        return $this->getDoctrine()->getManager()->getRepository($name);
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->kernel->getContainer()->get('doctrine');
    }
}