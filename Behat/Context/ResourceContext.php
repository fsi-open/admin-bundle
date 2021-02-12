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
use Behat\Symfony2Extension\Context\KernelAwareContext;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class ResourceContext extends PageObjectContext implements KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^there are following resources added to resource map$/
     */
    public function thereAreFollowingResourcesAddedToResourceMap(TableNode $resources)
    {
        foreach ($resources->getHash() as $resource) {
            expect($this->getResourceMapBuilder()->hasResource($resource['Key']))->toBe(true);

            if (isset($resource['Type'])) {
                expect($this->getResourceMapBuilder()->getResource($resource['Key']))->toBeAnInstanceOf(
                    sprintf(
                        'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\%sType',
                        ucfirst($resource['Type'])
                    )
                );
            }
        }
    }

    /**
     * @Given /^I fill form "Content" field with "([^"]*)"$/
     */
    public function iFillFormFieldWith($value)
    {
        $this->getElement('Form')->fillField('Content', $value);
    }

    /**
     * @Given /^I should see form "Content" field with value "([^"]*)"$/
     */
    public function iShouldSeeFormFieldWithValue($value)
    {
        expect($this->getElement('Form')->findField('Content')->getValue())->toBe($value);
    }

    private function getResourceMapBuilder(): MapBuilder
    {
        return $this->kernel->getContainer()->get('test.fsi_resource_repository.map_builder');
    }
}
