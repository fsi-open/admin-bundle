<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractContext implements KernelAwareContext, MinkAwareContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Mink
     */
    private $mink;

    /**
     * @var array
     */
    private $minkParameters;

    /**
     * @var Generator|null
     */
    private $faker;

    /**
     * @var EntityManagerInterface|null
     */
    private $entityManager;

    public function setMink(Mink $mink): void
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters): void
    {
        $this->minkParameters = $parameters;
    }

    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }

    protected function getRepository($className): EntityRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            /** @var EntityManagerInterface $manager */
            $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
            $this->entityManager = $entityManager;
        }

        return $this->entityManager;
    }

    protected function getFaker(): Generator
    {
        if (null === $this->faker) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }

    protected function getMinkParameters(): array
    {
        return $this->minkParameters;
    }

    protected function getSession($name = null): Session
    {
        return $this->mink->getSession($name);
    }

    protected function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->kernel->getContainer();
    }

    protected function isSeleniumDriverUsed(): bool
    {
        return $this->getSession()->getDriver() instanceof Selenium2Driver;
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
