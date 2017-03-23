<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\ORM\Doctrine\Populator;
use FSi\FixturesBundle\Entity\News;
use FSi\FixturesBundle\Entity\Tag;
use InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DataContext extends AbstractContext
{
    /**
     * @BeforeScenario
     */
    public function createDatabase()
    {
        $this->deleteDatabaseIfExist();
        $metadata = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->getEntityManager());
        $tool->createSchema($metadata);
    }

    /**
     * @AfterScenario
     */
    public function deleteDatabaseIfExist()
    {
        $dbFilePath = $this->getKernel()->getRootDir() . '/data.sqlite';

        if (file_exists($dbFilePath)) {
            unlink($dbFilePath);
        }
    }

    /**
     * @Transform :className
     */
    public function entityToClassName($entityName)
    {
        switch ($entityName) {
            case "news":
                return "FSi\FixturesBundle\Entity\News";
            case "subscriber":
            case "subscribers":
                return "FSi\FixturesBundle\Entity\Subscriber";
            case "person":
                return "FSi\FixturesBundle\Entity\Person";
        }
    }

    /**
     * @Given there are :count :className
     * @Given there is :count :className
     */
    public function thereIsNumberOfEntities($count, $className)
    {
        $populator = new Populator($this->getFaker(), $this->getEntityManager());
        $populator->addEntity(
            $className,
            $count,
            $this->getColumnFormatters($className),
            $this->getModifiers($className)
        );

        $populator->execute();
        expect(count($this->getRepository($className)->findAll()))->toBe($count);
    }

    /**
     * @Given there is a :className with :field :value present in the database
     */
    public function thereIsAnEntityWithField($className, $field, $value)
    {
        $formatters = $this->getColumnFormatters($className);
        $formatters[$field] = $this->parseScenarioValue($value);
        $populator = new Populator($this->getFaker(), $this->getEntityManager());
        $populator->addEntity(
            $className,
            1,
            $formatters,
            $this->getModifiers($className)
        );

        $populator->execute();
        expect($this->getRepository($className)->findOneBy([$field => $value]))->toBeAnInstanceOf($className);
    }

    /**
     * @Then there should be a :className with :field :value present in the database
     */
    public function entityWithFieldShouldExist($className, $field, $value)
    {
        expect($this->getRepository($className)->findOneBy([$field => $value]))->toBeAnInstanceOf($className);
    }

    /**
     * @Given :className with :field :value should not exist in database anymore
     */
    public function entityShouldNotExistInDatabaseAnymore($className, $field, $value)
    {
        expect($this->getRepository($className)->findOneBy([$field => $value]))->toBe(null);
    }

    /**
     * @Then new :className should be created
     */
    public function newEntityShouldBeCreated($className)
    {
        $this->thereShouldExistsNumberOfEntities(1, $className);
    }

    /**
     * @Then there should not be any :className present in the database
     */
    public function thereShouldNotBeAnyEntities($className)
    {
        $this->thereShouldExistsNumberOfEntities(0, $className);
    }

    /**
     * @Then there should be :count :className present in the database
     */
    public function thereShouldExistsNumberOfEntities($count, $className)
    {
        expect(count($this->getRepository($className)->findAll()))->toBe($count);
    }

    /**
     * @Given /^the following news exist in database$/
     */
    public function followingNewsExistInDatabase(TableNode $table)
    {
        $manager = $this->getEntityManager();
        $faker = $this->getFaker();
        foreach ($table->getHash() as $newsNode) {
            $news = $this->getRepository('FSi\FixturesBundle\Entity\News')->findOneByTitle($newsNode['Title']);
            if (!isset($news)) {
                $news = new News();
            }

            $news->setTitle($newsNode['Title']);
            if (isset($newsNode['Date']) && $newsNode['Date']) {
                $news->setDate(\DateTime::createFromFormat('Y-m-d', $newsNode['Date']));
            }
            $news->setCreatedAt($faker->dateTime());
            $news->setVisible($faker->boolean());
            $news->setCreatorEmail($faker->email());

            $manager->persist($news);
        }

        $manager->flush();
    }

    /**
     * @Then :className with :field :value should have :expectedCount elements in collection :collectionName
     */
    public function entityShouldHaveElementsInCollection($className, $field, $value, $expectedCount, $collectionName)
    {
        $entity = $this->getRepository($className)->findOneBy([$field => $value]);
        $this->getEntityManager()->refresh($entity);

        expect(count($this->getEntityField($entity, $collectionName)))->toBe($expectedCount);
    }

    /**
     * @Then :className with id :id should have changed :field to :value
     */
    public function entityWithIdShouldHaveChangedField($className, $id, $field, $value)
    {
        $entity = $this->getRepository($className)->find($id);
        $this->getEntityManager()->refresh($entity);

        expect($this->getEntityField($entity, $field))->toBe($value);
    }

    /**
     * @Then :className with id :id should not have his :field changed to :value
     */
    public function entityWithIdShouldNotHaveChangedFieldValue($className, $id, $field, $value)
    {
        $entity = $this->getRepository($className)->find($id);
        $this->getEntityManager()->refresh($entity);

        expect($this->getEntityField($entity, $field))->notToBe($value);
    }

    /**
     * @param string $className
     * @return array
     * @throws InvalidArgumentException
     */
    private function getColumnFormatters($className)
    {
        $faker = $this->getFaker();
        switch ($className) {
            case 'FSi\FixturesBundle\Entity\News':
                return [
                    'creatorEmail' => function() use ($faker) {
                        return $faker->email();
                    },
                    'categories' => function() use ($faker) {
                        return [$faker->text(), $faker->text()];
                    },
                    'photoKey' => null,
                    'visible' => true
                ];
            case 'FSi\FixturesBundle\Entity\Person':
                return [
                    'email' => function() use ($faker) { return $faker->email(); },
                ];
            case 'FSi\FixturesBundle\Entity\Subscriber':
                return [
                    'email' => function() use ($faker) { return $faker->email(); },
                    'active' => true
                ];
            default:
                throw new InvalidArgumentException(sprintf(
                    'Cannot find any column formatters for class "%s',
                    $className
                ));
        }
    }

    /**
     * @param string $className
     * @return array
     * @throws InvalidArgumentException
     */
    private function getModifiers($className)
    {
        $faker = $this->getFaker();
        switch ($className) {
            case 'FSi\FixturesBundle\Entity\News':
                return [
                    function(News $news) use ($faker) {
                        $tag = new Tag();
                        $tag->setName($faker->sentence());
                        $tag->setNews($news);
                        $news->setTags([$tag]);
                    }
                ];
            case 'FSi\FixturesBundle\Entity\Person':
            case 'FSi\FixturesBundle\Entity\Subscriber':
                return [];
            default:
                throw new InvalidArgumentException(sprintf(
                    'Cannot find any modifiers for class "%s',
                    $className
                ));
        }
    }

    private function getEntityField($entity, $field)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        return $propertyAccessor->getValue($entity, strtolower($field));
    }
}
