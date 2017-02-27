<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use FSi\Bundle\AdminBundle\Behat\Element\Form;
use FSi\Bundle\AdminBundle\Behat\Page\DefaultPage;

class FormContext extends AbstractContext
{
    /**
     * @var DefaultPage
     */
    private $defaultPage;

    public function __construct(DefaultPage $defaultPage)
    {
        $this->defaultPage = $defaultPage;
    }

    /**
     * @When I change form field :field to value :value
     */
    public function iChangeFormFieldWithValue($field, $value)
    {
        expect($this->getFormElement()->findField($field)->getValue())->toNotBe($value);
        $this->getFormElement()->fillField($field, $value);
    }

    /**
     * @Given /^I should see form with following fields$/
     */
    public function iShouldSeeFormWithFollowingFields(TableNode $table)
    {
        $form = $this->getFormElement();
        foreach($table->getHash() as $fieldRow) {
            expect($form->hasField($fieldRow['Field name']))->toBe(true);
        }
    }

    /**
     * @Given /^I press form "([^"]*)" button$/
     */
    public function iPressFormButton($button)
    {
        $this->getFormElement()->pressButton($button);
    }

    /**
     * @Given I fill the form with values:
     */
    public function iFillFormFields(TableNode $table)
    {
        $form = $this->getFormElement();
        foreach($table->getHash() as $fieldRow) {
            $fieldName = $fieldRow['Field name'];
            $fieldValue = $fieldRow['Field value'];
            expect($form->hasField($fieldName))->toBe(true);
            $field = $form->findField($fieldName);
            if ($field->getAttribute('type') === 'checkbox') {
                $this->parseScenarioValue($fieldValue) ? $field->check() : $field->uncheck();
            } else {
                $field->setValue($fieldValue);
            }
        }
    }

    /**
     * @return Form
     */
    private function getFormElement()
    {
        return $this->defaultPage->getElement('Form');
    }
}
