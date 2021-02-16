<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use FSi\Bundle\AdminBundle\Behat\Element\Messages;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class MessageContext extends PageObjectContext
{
    /**
     * @Then I should see an error message saying:
     */
    public function iShouldSeeErrorMessageSaying(PyStringNode $message): void
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('danger'))->toBe($message->getRaw());
    }

    /**
     * @Then I should see a warning message saying:
     */
    public function iShouldSeeWarningMessageSaying(PyStringNode $message): void
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('warning'))->toBe($message->getRaw());
    }

    /**
     * @Then I should see an informational message saying:
     */
    public function iShouldSeeInformationalMessageSaying(PyStringNode $message): void
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('info'))->toBe($message->getRaw());
    }

    /**
     * @Then I should see a success message saying:
     */
    public function iShouldSeeSuccessMessageSaying(PyStringNode $message): void
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('success'))->toBe($message->getRaw());
    }

    private function getMessagesElement(): Messages
    {
        return $this->getElement('Messages');
    }
}
