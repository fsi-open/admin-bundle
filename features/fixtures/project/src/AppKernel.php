<?php

declare(strict_types=1);

namespace FSi;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new \FSi\Bundle\DoctrineExtensionsBundle\FSiDoctrineExtensionsBundle(),
            new \FSi\Bundle\DataSourceBundle\DataSourceBundle(),
            new \FSi\Bundle\DataGridBundle\DataGridBundle(),
            new \FSi\Bundle\AdminBundle\FSiAdminBundle(),
            new \FSi\FixturesBundle\FSiFixturesBundle(),
            new \FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(sprintf('%s/../app/config/config.yml', __DIR__));
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache';
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/logs';
    }
}
