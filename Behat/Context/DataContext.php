<?php
/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use FSi\FixturesBundle\Entity\News;
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

    public function getNewsCount()
    {
        return count($this->getDoctrine()->getManager()->getRepository('FSi\FixturesBundle\Entity\News')->findAll());
    }

    public function getSubscribersCount()
    {
        return count($this->getDoctrine()->getManager()->getRepository('FSi\FixturesBundle\Entity\Subscriber')->findAll());
    }

    public function findNewsById($id)
    {
        return $this->getDoctrine()->getManager()->getRepository('FSi\FixturesBundle\Entity\News')->findOneById($id);
    }

    public function findSubscriberById($id)
    {
        return $this->getDoctrine()->getManager()->getRepository('FSi\FixturesBundle\Entity\Subscriber')->findOneById($id);
    }

    /**
     * @Given /^the following news exist in database$/
     */
    public function followingNewsExistInDatabase(TableNode $table)
    {
        $generator = Factory::create();
        foreach ($table->getHash() as $newsNode) {
            $news = $this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findOneByTitle($newsNode['Title']);
            if (!isset($news)) {
                $news = new News();
            }

            $news->setTitle($newsNode['Title']);
            if (isset($newsNode['Date']) && $newsNode['Date']) {
                $news->setDate(\DateTime::createFromFormat('Y-m-d', $newsNode['Date']));
            }
            $news->setCreatedAt($generator->dateTime());
            $news->setVisible($generator->boolean());
            $news->setCreatorEmail($generator->email());

            $this->getDoctrine()->getManager()->persist($news);
            $this->getDoctrine()->getManager()->flush();
        }
    }

    /**
     * @Given /^there are (\d+) news in database$/
     * @Given /^there is (\d+) news in database$/
     */
    public function thereAreNewsInDatabase($newsCount)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\FixturesBundle\Entity\News', $newsCount, array(
            'creatorEmail' => function() use ($generator) { return $generator->email(); },
            'categories' => function() use($generator) {return array($generator->text(), $generator->text());}
        ));
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findAll()))->toBe($newsCount);
    }

    /**
     * @Given /^there is news with id (\d+) in database$/
     */
    public function thereIsNewsWithIdInDatabase($id)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\FixturesBundle\Entity\News', 1, array(
            'id' => $id,
            'creatorEmail' => function() use ($generator) { return $generator->email(); }
        ));
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findAll()))->toBe(1);
    }

    /**
     * @Then /^there should be news with "([^"]*)" title in database$/
     */
    public function thereShouldBeNewsWithTitleInDatabase($title)
    {
        expect($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findOneByTitle($title))
            ->toBeAnInstanceOf('FSi\FixturesBundle\Entity\News');
    }

    /**
     * @Given /^news "([^"]*)" should not exist in database anymore$/
     */
    public function newsShouldNotExistInDatabaseAnymore($title)
    {
        expect($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findOneBy(array(
            'title' => $title
        )))->toBe(null);
    }


    /**
     * @Given /^there should not be any news in database$/
     */
    public function thereShouldNotBeAnyNewsInDatabase()
    {
        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findAll()))->toBe(0);
    }

    /**
     * @Given /^there is (\d+) subscriber in database$/
     * @Given /^there are (\d+) subscribers in database$/
     */
    public function thereAreSubscribersInDatabase($count)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\FixturesBundle\Entity\Subscriber', $count, array(
            'email' => function() use ($generator) { return $generator->email(); }
        ));
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\Subscriber')->findAll()))->toBe($count);
    }

    /**
     * @Given /^there is subscriber with id (\d+) in database$/
     */
    public function thereIsSubscriberWithIdInDatabase($id)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\FixturesBundle\Entity\Subscriber', 1, array(
            'id' => $id,
            'email' => function() use ($generator) { return $generator->email(); }
        ));
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\Subscriber')->findAll()))->toBe(1);
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
