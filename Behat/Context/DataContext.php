<?php
/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use FSi\FixturesBundle\Entity\News;
use FSi\FixturesBundle\Entity\Tag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DataContext implements KernelAwareContext
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
     * @Then /^new news should be created$/
     */
    public function newNewsShouldBeCreated()
    {
        $this->thereShouldBeNewsInDatabase(1);
    }

    /**
     * @Then /^new subscriber should be created$/
     */
    public function newSubscriberShouldBeCreated()
    {
        $this->thereShouldBeSubscribersInDatabase(1);
    }

    /**
     * @Then /^there should be (\d+) news in database$/
     */
    public function thereShouldBeNewsInDatabase($newsCount)
    {
        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findAll()))->toBe($newsCount);
    }

    /**
     * @Given /^the following news exist in database$/
     */
    public function followingNewsExistInDatabase(TableNode $table)
    {
        $manager = $this->getDoctrine()->getManager();
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

            $manager->persist($news);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are (\d+) news in database$/
     * @Given /^there is (\d+) news in database$/
     */
    public function thereAreNewsInDatabase($newsCount)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\FixturesBundle\Entity\News', $newsCount, [
            'creatorEmail' => function() use ($generator) { return $generator->email(); },
            'categories' => function() use($generator) {return [$generator->text(), $generator->text()];},
            'photoKey' => null
        ], [function(News $news) use($generator) {
            $tag = new Tag();
            $tag->setName($generator->sentence());
            $tag->setNews($news);
            $news->setTags([$tag]);
        }]);
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

        $populator->addEntity('FSi\FixturesBundle\Entity\News', 1, [
            'id' => $id,
            'creatorEmail' => function() use ($generator) { return $generator->email(); },
            'photoKey' => null
        ]);
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
        expect($this->getEntityRepository('FSi\FixturesBundle\Entity\News')->findOneBy([
            'title' => $title
        ]))->toBe(null);
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

        $populator->addEntity('FSi\FixturesBundle\Entity\Subscriber', $count, [
            'email' => function() use ($generator) { return $generator->email(); }
        ]);
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

        $populator->addEntity('FSi\FixturesBundle\Entity\Subscriber', 1, [
            'id' => $id,
            'email' => function() use ($generator) { return $generator->email(); }
        ]);
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\Subscriber')->findAll()))->toBe(1);
    }

    /**
     * @Given /^there should be (\d+) subscribers in database$/
     */
    public function thereShouldBeSubscribersInDatabase($count)
    {
        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\Subscriber')->findAll()))->toBe($count);
    }

    /**
     * @Given /^there should not be any subscribers in database$/
     */
    public function thereShouldNotBeAnySubscribersInDatabase()
    {
        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\Subscriber')->findAll()))->toBe(0);
    }

    /**
     * @Given /^there is a person with id (\d+) in database$/
     */
    public function thereIsAPersonWithIdInDatabase($id)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getDoctrine()->getManager());

        $populator->addEntity('FSi\FixturesBundle\Entity\Person', 1, [
            'id' => $id,
            'email' => function() use ($generator) { return $generator->email(); }
        ]);
        $populator->execute();

        expect(count($this->getEntityRepository('FSi\FixturesBundle\Entity\Person')->findAll()))->toBe(1);
    }

    /**
     * @Then there should be a person with id :id in database
     */
    public function thereShouldBeAPersonWithId($id)
    {
        $class = 'FSi\FixturesBundle\Entity\Person';
        expect($this->getEntityRepository($class)->find($id))->beAnInstanceOf($class);
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

    /**
     * @Then /^news should have (\d+) elements in collection "([^"]*)"$/
     */
    public function newsShouldHaveElementsInCollection($expectedCount, $collectionName)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->clear();

        $news = $manager
            ->getRepository('FSi\FixturesBundle\Entity\News')
            ->findBy([], [], 1);
        $news = reset($news);

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $tags = $propertyAccessor->getValue($news, strtolower($collectionName));

        expect(count($tags))->toBe($expectedCount);
    }
}
