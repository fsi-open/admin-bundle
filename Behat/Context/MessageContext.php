<?php

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use FSi\Bundle\AdminBundle\Behat\Context\Page\Element\Messages;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class MessageContext extends PageObjectContext
{
    /**
     * @Then I should see an error message saying:
     */
    public function iShouldSeeErrorMessageSaying(PyStringNode $message)
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('danger'))->toBe($message->getRaw());
    }

    /**
     * @Then I should see a warning message saying:
     */
    public function iShouldSeeWarningMessageSaying(PyStringNode $message)
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('warning'))->toBe($message->getRaw());
    }

    /**
     * @Then I should see an informational message saying:
     */
    public function iShouldSeeInformationalMessageSaying(PyStringNode $message)
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('info'))->toBe($message->getRaw());
    }

    /**
     * @Then I should see a success message saying:
     */
    public function iShouldSeeSuccessMessageSaying(PyStringNode $message)
    {
        $notifications = $this->getMessagesElement();

        expect($notifications->getMessageText('success'))->toBe($message->getRaw());
    }

    /**
     * @return Messages
     */
    private function getMessagesElement()
    {
        return $this->getElement('Messages');
    }
}
