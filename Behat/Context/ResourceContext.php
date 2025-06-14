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
use Doctrine\ORM\EntityManagerInterface;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\AbstractType;

class ResourceContext extends AbstractContext
{
    private MapBuilder $mapBuilder;

    public function __construct(
        Session $session,
        MinkParameters $minkParameters,
        EntityManagerInterface $entityManager,
        MapBuilder $mapBuilder
    ) {
        parent::__construct($session, $minkParameters, $entityManager);

        $this->mapBuilder = $mapBuilder;
    }

    /**
     * @Given /^there are following resources added to resource map$/
     */
    public function thereAreFollowingResourcesAddedToResourceMap(TableNode $resources): void
    {
        foreach ($resources->getHash() as $resource) {
            Assertion::true($this->mapBuilder->hasResource($resource['Key']));

            if (isset($resource['Type'])) {
                /** @var class-string<AbstractType> $className */
                $className = sprintf(
                    'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\%sType',
                    ucfirst($resource['Type'])
                );
                Assertion::isInstanceOf($this->mapBuilder->getResource($resource['Key']), $className);
            }
        }
    }

    /**
     * @Given /^I fill form "Content" field with "([^"]*)"$/
     */
    public function iFillFormFieldWith($value): void
    {
        $this->getSession()->getPage()->find('css', 'form')->fillField('Content', $value);
    }

    /**
     * @Given /^I should see form "Content" field with value "([^"]*)"$/
     */
    public function iShouldSeeFormFieldWithValue($value): void
    {
        Assertion::eq($this->getSession()->getPage()->findField('Content')->getValue(), $value);
    }
}
