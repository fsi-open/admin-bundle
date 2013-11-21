<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DeveloperContext extends BehatContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^the bundle is registered in application$/
     */
    public function theBundleIsRegisteredInApplication()
    {
        expect($this->kernel->getBundle('FSiAdminBundle'))->toBeAnInstanceOf('FSi\Bundle\AdminBundle\FSiAdminBundle');
    }

    /**
     * @Given /^routing is configured in application$/
     */
    public function routingIsConfiguredInApplication()
    {
        $routes = $this->kernel->getContainer()->get('router')->getRouteCollection()->all();
        expect(array_key_exists('fsi_admin', $routes))->toBe(true);
        expect(array_key_exists('fsi_admin_crud_list', $routes))->toBe(true);
        expect(array_key_exists('fsi_admin_crud_create', $routes))->toBe(true);
        expect(array_key_exists('fsi_admin_crud_edit', $routes))->toBe(true);
        expect(array_key_exists('fsi_admin_crud_delete', $routes))->toBe(true);
        expect(array_key_exists('fsi_admin_resource', $routes))->toBe(true);
    }

    /**
     * @Given /^translations are enabled in application$/
     */
    public function translationsAreEnabledInApplication()
    {
        expect($this->kernel->getContainer()->get('translator'))->toBeAnInstanceOf('Symfony\Bundle\FrameworkBundle\Translation\Translator');
    }
}