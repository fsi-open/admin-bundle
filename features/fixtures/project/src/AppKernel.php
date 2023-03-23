<?php

declare(strict_types=1);

namespace FSi;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * @return array<BundleInterface>
     */
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new \FSi\Component\Files\Integration\Symfony\FilesBundle(),
            new \FSi\Bundle\DataSourceBundle\DataSourceBundle(),
            new \FSi\Bundle\DataGridBundle\DataGridBundle(),
            new \FSi\Bundle\AdminBundle\FSiAdminBundle(),
            new \FSi\FixturesBundle\FSiFixturesBundle(),
            new \FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),
            new \FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle(),
            new \FSi\Component\Translatable\Integration\Symfony\TranslatableBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(sprintf('%s/../config/config.yaml', __DIR__));
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/features/fixtures/project/var/cache';
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/features/fixtures/project/var/logs';
    }
}
