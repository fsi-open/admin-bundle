<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use DateTime;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\ORM\Doctrine\Populator;
use FSi\FixturesBundle\Entity\Category;
use FSi\FixturesBundle\Entity\News;
use FSi\FixturesBundle\Entity\Person;
use FSi\FixturesBundle\Entity\Subscriber;
use FSi\FixturesBundle\Entity\Tag;
use InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DataContext extends AbstractContext
{
    /**
     * @BeforeScenario
     */
    public function createDatabase(): void
    {
        $this->deleteDatabaseIfExist();
        $metadata = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->getEntityManager());
        $tool->createSchema($metadata);
    }

    /**
     * @AfterScenario
     */
    public function deleteDatabaseIfExist(): void
    {
        $dbFilePath = $this->getKernel()->getRootDir() . '/data.sqlite';

        if (file_exists($dbFilePath)) {
            unlink($dbFilePath);
        }
    }

    /**
     * @Transform :className
     */
    public function entityToClassName(string $entityName): string
    {
        switch ($entityName) {
            case 'news':
                return News::class;
            case 'category':
            case 'categories':
                return Category::class;
            case 'subscriber':
            case 'subscribers':
                return Subscriber::class;
            case 'person':
                return Person::class;
        }
    }

    /**
     * @Given there are :count :className
     * @Given there is :count :className
     */
    public function thereIsNumberOfEntities($count, string $className)
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
    public function thereIsAnEntityWithField(string $className, string $field, $value)
    {
        $formatters = $this->getColumnFormatters($className);
        $formatters[$field] = $this->parseScenarioValue((string) $value);
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
    public function entityWithFieldShouldExist(string $className, string $field, $value)
    {
        expect($this->getRepository($className)->findOneBy([$field => $value]))->toBeAnInstanceOf($className);
    }

    /**
     * @Given :className with :field :value should not exist in database anymore
     */
    public function entityShouldNotExistInDatabaseAnymore(string $className, string $field, $value)
    {
        expect($this->getRepository($className)->findOneBy([$field => $value]))->toBe(null);
    }

    /**
     * @Then new :className should be created
     */
    public function newEntityShouldBeCreated(string $className)
    {
        $this->thereShouldExistsNumberOfEntities(1, $className);
    }

    /**
     * @Then there should not be any :className present in the database
     */
    public function thereShouldNotBeAnyEntities(string $className)
    {
        $this->thereShouldExistsNumberOfEntities(0, $className);
    }

    /**
     * @Then there should be :count :className present in the database
     */
    public function thereShouldExistsNumberOfEntities($count, string $className)
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
        $newsRepository = $this->getRepository(News::class);
        $categoryRepository = $this->getRepository(Category::class);

        foreach ($table->getHash() as $newsNode) {
            $news = $newsRepository->findOneByTitle($newsNode['Title']);
            if (!isset($news)) {
                $news = new News();
            }

            $news->setTitle($newsNode['Title']);
            if (isset($newsNode['Date']) && $newsNode['Date']) {
                $news->setDate(DateTime::createFromFormat('Y-m-d', $newsNode['Date']));
            }
            if (isset($newsNode['Category']) && $newsNode['Category']) {
                /** @var Category|null $category */
                $category = $categoryRepository->findOneBy(['title' => $newsNode['Category']]);

                if ($category === null) {
                    throw new InvalidArgumentException(sprintf(
                        'Can\'t find category by title "%s"',
                        $newsNode['Category']
                    ));
                }

                $news->addCategory($category);
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
    public function entityShouldHaveElementsInCollection(
        string $className,
        string $field,
        $value,
        $expectedCount,
        string $collectionName
    ) {
        $entity = $this->getRepository($className)->findOneBy([$field => $value]);
        $this->getEntityManager()->refresh($entity);

        expect(count($this->getEntityField($entity, $collectionName)))->toBe($expectedCount);
    }

    /**
     * @Then :className with id :id should have changed :field to :value
     */
    public function entityWithIdShouldHaveChangedField(string $className, $id, string $field, $value)
    {
        $entity = $this->getRepository($className)->find($id);
        $this->getEntityManager()->refresh($entity);

        expect($this->getEntityField($entity, $field))->toBe($value);
    }

    /**
     * @Then :className with id :id should not have his :field changed to :value
     */
    public function entityWithIdShouldNotHaveChangedFieldValue(string $className, $id, string $field, $value)
    {
        $entity = $this->getRepository($className)->find($id);
        $this->getEntityManager()->refresh($entity);

        expect($this->getEntityField($entity, $field))->notToBe($value);
    }

    private function getColumnFormatters(string $className): array
    {
        $faker = $this->getFaker();
        switch ($className) {
            case News::class:
                return [
                    'creatorEmail' => function() use ($faker) {
                        return $faker->email();
                    },
                    'categories' => function() use ($faker) {
                        $categories = $this->getRepository(Category::class)->findAll();

                        if (count($categories)) {
                            return [$faker->randomElement($categories)];
                        }

                        return [];
                    },
                    'photoKey' => null,
                    'visible' => true
                ];
            case Category::class:
                return [];
            case Person::class:
                return [
                    'email' => function() use ($faker) { return $faker->email(); },
                ];
            case Subscriber::class:
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

    private function getModifiers(string $className): array
    {
        $faker = $this->getFaker();
        switch ($className) {
            case News::class:
                return [
                    function(News $news) use ($faker) {
                        $tag = new Tag();
                        $tag->setName($faker->sentence());
                        $tag->setNews($news);
                        $news->setTags([$tag]);
                    }
                ];
            case Person::class:
            case Subscriber::class:
            case Category::class:
                return [];
            default:
                throw new InvalidArgumentException(sprintf(
                    'Cannot find any modifiers for class "%s',
                    $className
                ));
        }
    }

    /**
     * @param object $entity
     * @param string $field
     * @return mixed
     */
    private function getEntityField($entity, string $field)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        return $propertyAccessor->getValue($entity, strtolower($field));
    }
}
