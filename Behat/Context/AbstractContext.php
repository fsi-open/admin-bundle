<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Faker\Factory;
use Faker\Generator;
use FriendsOfBehat\PageObjectExtension\Element\Element;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;

abstract class AbstractContext implements Context
{
    private Session $session;
    private MinkParameters $minkParameters;
    private EntityManagerInterface $entityManager;
    private Generator $faker;

    public function __construct(Session $session, MinkParameters $minkParameters, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->minkParameters = $minkParameters;
        $this->entityManager = $entityManager;
        $this->faker = Factory::create();
    }

    protected function getRepository($className): EntityRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getFaker(): Generator
    {
        return $this->faker;
    }

    protected function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @template T of Element
     * @param class-string<T> $elementClass
     * @return T
     */
    protected function getElement(string $elementClass): Element
    {
        return new $elementClass($this->session, $this->minkParameters);
    }

    /**
     * @template T of Page
     * @param class-string<T> $pageClass
     * @return T
     */
    protected function getPage(string $pageClass): Page
    {
        return new $pageClass($this->session, $this->minkParameters);
    }

    protected function isSeleniumDriverUsed(): bool
    {
        return $this->session->getDriver() instanceof Selenium2Driver;
    }

    protected function parseScenarioValue($rawValue)
    {
        $value = trim($rawValue);
        switch ($value) {
            case 'false':
                return false;
            case 'true':
                return true;
            default:
                return $value;
        }
    }
}
