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
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class DisplayContext extends PageObjectContext implements KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I should see display with following fields$/
     */
    public function iShouldSeeDisplayWithFollowingFields(TableNode $table): void
    {
        $display = $this->getPage('News display')->getElement('Display');
        foreach ($table->getHash() as $row) {
            expect($display->hasFieldWithName($row['Field name']))->toBe(true);
        }
    }
}
