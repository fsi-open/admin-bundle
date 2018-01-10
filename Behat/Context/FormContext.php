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
use Behat\Mink\Element\NodeElement;
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
     * @Transform /"([^"]*)" non-editable collection/
     * @Transform /non-editable collection "([^"]*)"/
     * @Transform /removable-only collection "([^"]*)"/
     */
    public function transformToNoneditableCollection($collectionNames)
    {
        return $this->defaultPage->getNonEditableCollection($collectionNames);
    }

    /**
     * @Transform /^"([^"]*)" collection/
     * @Transform /collection "([^"]*)"/
     */
    public function transformToCollection($collectionNames)
    {
        return $this->defaultPage->getCollection($collectionNames);
    }

    /**
     * @Given /^("[^"]*" collection) has (\d+) (element|elements)$/
     * @Then /^("[^"]*" collection) should have (\d+) (element|elements)$/
     * @Given /^(non-editable collection "[^"]*") has (\d+) (element|elements)$/
     * @Then /^(non-editable collection "[^"]*") should have (\d+) (element|elements)$/
     * @Then /^(removable-only collection "[^"]*") should have (\d+) (element|elements)$/
     */
    public function collectionShouldHaveElements(NodeElement $collection, $elementsCount)
    {
        $elements = $collection->findAll('xpath', '/*/*[@class = "form-group"]');
        expect(count($elements))->toBe($elementsCount);
    }

    /**
     * @Given /^(collection "[^"]*") should have "([^"]*)" button$/
     */
    public function collectionShouldHaveButton(NodeElement $collection, $buttonName)
    {
        expect($collection->findButton($buttonName))->toNotBeNull();
    }

    /**
     * @Then /^all buttons for adding and removing items in (non-editable collection "[^"]*") should be disabled$/
     */
    public function allCollectionButtonsDisabled(NodeElement $collection)
    {
        $removeButtons = $collection->findAll('css', '.collection-remove');
        expect(count($removeButtons))->notToBe(0);
        foreach ($removeButtons as $removeButton) {
            expect($removeButton->hasClass('disabled'))->toBe(true);
        }
        $addButtons = $collection->findAll('css', '.collection-add');
        expect(count($addButtons))->notToBe(0);
        foreach ($addButtons as $addButton) {
            expect($addButton->hasClass('disabled'))->toBe(true);
        }
    }

    /**
     * @Then /^button for adding item in (removable-only collection "[^"]*") should be disabled$/
     */
    public function collectionAddButtonIsDisabled(NodeElement $collection)
    {
        $addButtons = $collection->findAll('css', '.collection-add');
        expect(count($addButtons))->notToBe(0);
        foreach ($addButtons as $addButton) {
            expect($addButton->hasClass('disabled'))->toBe(true);
        }
    }

    /**
     * @Then /^buttons for removing items in (removable-only collection "[^"]*") should be enabled/
     */
    public function collectionRemoveButtonsAreEnabled(NodeElement $collection)
    {
        $addButtons = $collection->findAll('css', '.collection-remove');
        expect(count($addButtons))->notToBe(0);
        foreach ($addButtons as $addButton) {
            expect($addButton->hasClass('disabled'))->toBe(false);
        }
    }

    /**
     * @When /^I press "([^"]*)" in (collection "[^"]*")$/
     */
    public function iPressInCollection($buttonName, NodeElement $collection)
    {
        $collection
            ->find('xpath', '/*[contains(concat(" ",normalize-space(@class)," ")," collection-add ")]')
            ->press();
    }

    /**
     * @Given /^I fill "([^"]*)" with "([^"]*)" in (collection "[^"]*") at position (\d+)$/
     */
    public function iFillWithInCollectionAtPosition($fieldName, $fieldValue, NodeElement $collection, $position)
    {
        $collectionRow = $collection->find('xpath', sprintf('/*/*[@class = "form-group"][%d]', $position));
        $collectionRow->fillField($fieldName, $fieldValue);
    }

    /**
     * @Given /^I remove (\w+) element in (collection "[^"]*")$/
     * @Given /^I remove (\w+) element in (removable-only collection "[^"]*")$/
     */
    public function iRemoveElementInCollection($index, NodeElement $collection)
    {
        $collection->find('xpath', sprintf('/*/*[@class = "form-group"][%d]', $index))
             ->find('css', '.collection-remove')->click();
    }

    private function getFormElement(): Form
    {
        return $this->defaultPage->getElement('Form');
    }
}
