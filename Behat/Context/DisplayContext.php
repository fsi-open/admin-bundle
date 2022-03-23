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
use FSi\Bundle\AdminBundle\Behat\Element\Display;

class DisplayContext extends AbstractContext
{
    /**
     * @Given /^I should see display with following fields$/
     */
    public function iShouldSeeDisplayWithFollowingFields(TableNode $table): void
    {
        $display = $this->getElement(Display::class);
        foreach ($table->getHash() as $row) {
            expect($display->hasFieldWithName($row['Field name']))->toBe(true);
        }
    }
}
