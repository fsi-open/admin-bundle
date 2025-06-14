<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Assert\Assertion;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use FSi\FixturesBundle\Entity\Category;
use FSi\FixturesBundle\Entity\News;
use FSi\FixturesBundle\Entity\Person;
use FSi\FixturesBundle\Entity\Subscriber;
use FSi\FixturesBundle\Entity\Tag;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

use function array_key_exists;
use function count;
use function file_exists;

class DataContext extends AbstractContext
{
    private PropertyAccessor $propertyAccessor;

    public function __construct(
        Session $minkSession,
        MinkParameters $minkParameters,
        EntityManagerInterface $entityManager,
        PropertyAccessorInterface $propertyAccessor
    ) {
        parent::__construct($minkSession, $minkParameters, $entityManager);

        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @BeforeScenario
     */
    public function before(): void
    {
        $this->deleteDatabaseIfExist();
        $this->createDatabase();
    }

    /**
     * @AfterScenario
     */
    public function after(): void
    {
        $this->deleteDatabaseIfExist();
    }

    public function createDatabase(): void
    {

        $entityManager = $this->getEntityManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $tool = new SchemaTool($entityManager);
        $tool->createSchema($metadata);
    }

    public function deleteDatabaseIfExist(): void
    {
        $dbFilePath = __DIR__ . '/../../features/fixtures/project/var/data.sqlite';

        if (true === file_exists($dbFilePath)) {
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
            default:
                throw new InvalidArgumentException(sprintf('Unknown entity name "%s"', $entityName));
        }
    }

    /**
     * @Given there are :count :className
     * @Given there is :count :className
     */
    public function thereIsNumberOfEntities(int $count, string $className): void
    {
        $entityManager = $this->getEntityManager();
        for ($i = 0; $i < $count; $i++) {
            $instance = new $className();
            $this->applyEntityModifiers($instance);
            $this->applyFieldFormatters($instance);
            $entityManager->persist($instance);
        }

        $entityManager->flush();

        Assertion::count($this->getRepository($className)->findAll(), $count);
    }

    /**
     * @Given there is a :className with :field :value present in the database
     */
    public function thereIsAnEntityWithField(string $className, string $field, $value): void
    {
        $instance = new $className();
        $formatters = $this->getFieldFormatters($className);
        $formatters[$field] = function () use ($value) {
            return $this->parseScenarioValue((string) $value);
        };
        $this->applyEntityModifiers($instance);
        $this->applyFieldFormatters($instance, $formatters);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($instance);
        $entityManager->flush();

        Assertion::isInstanceOf($this->getRepository($className)->findOneBy([$field => $value]), $className);
    }

    /**
     * @Then there should be a :className with :field :value present in the database
     */
    public function entityWithFieldShouldExist(string $className, string $field, $value): void
    {
        Assertion::isInstanceOf($this->getRepository($className)->findOneBy([$field => $value]), $className);
    }

    /**
     * @Given :className with :field :value should not exist in database anymore
     */
    public function entityShouldNotExistInDatabaseAnymore(string $className, string $field, $value): void
    {
        Assertion::null($this->getRepository($className)->findOneBy([$field => $value]));
    }

    /**
     * @Then new :className should be created
     */
    public function newEntityShouldBeCreated(string $className): void
    {
        $this->thereShouldExistsNumberOfEntities(1, $className);
    }

    /**
     * @Then there should not be any :className present in the database
     */
    public function thereShouldNotBeAnyEntities(string $className): void
    {
        $this->thereShouldExistsNumberOfEntities(0, $className);
    }

    /**
     * @Then there should be :count :className present in the database
     */
    public function thereShouldExistsNumberOfEntities($count, string $className): void
    {
        Assertion::count($this->getRepository($className)->findAll(), $count);
    }

    /**
     * @Given /^the following news exist in database$/
     */
    public function followingNewsExistInDatabase(TableNode $table): void
    {
        $manager = $this->getEntityManager();
        $faker = $this->getFaker();
        $newsRepository = $this->getRepository(News::class);
        $categoryRepository = $this->getRepository(Category::class);

        foreach ($table->getHash() as $newsNode) {
            $news = $newsRepository->findOneByTitle($newsNode['Title']);
            if (null === $news) {
                $news = new News();
            }

            $news->setTitle($newsNode['Title']);
            if (true === array_key_exists('Date', $newsNode) && '' !== $newsNode['Date']) {
                $news->setDate(DateTime::createFromFormat('Y-m-d', $newsNode['Date']));
            }
            if (true === array_key_exists('Category', $newsNode) && '' !== $newsNode['Category']) {
                /** @var Category|null $category */
                $category = $categoryRepository->findOneBy(['title' => $newsNode['Category']]);

                if (null === $category) {
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
    ): void {
        $entity = $this->getRepository($className)->findOneBy([$field => $value]);
        $this->getEntityManager()->refresh($entity);

        Assertion::count($this->getEntityField($entity, $collectionName), $expectedCount);
    }

    /**
     * @Then :className with id :id should have changed :field to :value
     */
    public function entityWithIdShouldHaveChangedField(string $className, $id, string $field, $value): void
    {
        $entity = $this->getRepository($className)->find($id);
        $this->getEntityManager()->refresh($entity);

        Assertion::eq($this->getEntityField($entity, $field), $value);
    }

    /**
     * @Then :className with id :id should not have his :field changed to :value
     */
    public function entityWithIdShouldNotHaveChangedFieldValue(string $className, $id, string $field, $value): void
    {
        $entity = $this->getRepository($className)->find($id);
        $this->getEntityManager()->refresh($entity);

        Assertion::notEq($this->getEntityField($entity, $field), $value);
    }

    /**
     * @param object $instance
     * @return void
     * @throws InvalidArgumentException
     */
    private function applyFieldFormatters($instance, ?array $formatters = null): void
    {
        $className = get_class($instance);
        if (null === $formatters) {
            $formatters = $this->getFieldFormatters($className);
        }

        switch (true) {
            case $instance instanceof News:
                /** @var News $instance */

                /** @var string title */
                $title = $formatters['title']();
                $instance->setTitle($title);

                /** @var string $creatorEmail */
                $creatorEmail = $formatters['creatorEmail']();
                $instance->setCreatorEmail($creatorEmail);

                /** @var Category|null $categories */
                $categories = $formatters['categories']();
                array_walk(
                    $categories,
                    static function (Category $category, int $key, News $news): void {
                        $news->addCategory($category);
                    },
                    $instance
                );

                /** @var bool $visible */
                $visible = $formatters['visible']();
                $instance->setVisible($visible);
                break;
            case $instance instanceof Person:
                /** @var Person $instance */

                /** @var string $email */
                $email = $formatters['email']();
                $instance->setEmail($email);
                break;
            case $instance instanceof Subscriber:
                /** @var Subscriber $instance */

                /** @var string $email */
                $email = $formatters['email']();
                $instance->setEmail($email);

                /** @var bool $active */
                $active = $formatters['active']();
                $instance->setActive($active);
                break;
            case $instance instanceof Category:
                /** @var string title */
                $title = $formatters['title']();
                $instance->setTitle($title);
                break;
            default:
                throw new InvalidArgumentException(sprintf(
                    'Cannot find any column formatters for class "%s',
                    $className
                ));
        }
    }

    private function getFieldFormatters(string $className): array
    {
        $faker = $this->getFaker();
        $formatters = [
            News::class => [
                'title' => function () use ($faker): string {
                    return $faker->title();
                },
                'creatorEmail' => function () use ($faker): string {
                    return $faker->email();
                },
                'categories' => function () use ($faker): array {
                    /** @var array<Category> $categories */
                    $categories = $this->getRepository(Category::class)->findAll();
                    if (0 !== count($categories)) {
                        /** @var Category $randomCategory */
                        $randomCategory = $faker->randomElement($categories);
                        $categories = [$randomCategory];
                    }

                    return $categories;
                },
                'photoKey' => function (): ?string {
                    return null;
                },
                'visible' => function (): bool {
                    return true;
                }
            ],
            Person::class => [
                'email' => function () use ($faker): string {
                    return $faker->email();
                }
            ],
            Subscriber::class => [
                'email' => function () use ($faker): string {
                    return $faker->email();
                },
                'active' => function (): bool {
                    return true;
                }
            ],
            Category::class => [
                'title' => function () use ($faker): string {
                    return $faker->title();
                }
            ]
        ];

        if (false === array_key_exists($className, $formatters)) {
            throw new RuntimeException("No formatters for class \"{$className}\"");
        }

        return $formatters[$className];
    }

    /**
     * @param object $instance
     * @return void
     * @throws InvalidArgumentException
     */
    private function applyEntityModifiers($instance): void
    {
        $faker = $this->getFaker();
        switch (true) {
            case $instance instanceof News:
                $instance->setTitle($faker->title());
                $instance->setCreatedAt(new DateTime());
                $tag = new Tag();
                $tag->setName($faker->sentence());
                $tag->setNews($instance);
                $instance->setTags([$tag]);
                break;
            case $instance instanceof Subscriber:
                $instance->setCreatedAt(new DateTime());
                break;
            case $instance instanceof Person:
            case $instance instanceof Category:
                break;
            default:
                throw new InvalidArgumentException(sprintf(
                    'Cannot find any modifiers for class "%s',
                    get_class($instance)
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
        return $this->propertyAccessor->getValue($entity, strtolower($field));
    }
}
