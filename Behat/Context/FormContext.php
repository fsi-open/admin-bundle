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
use Behat\Mink\Element\NodeElement;
use FSi\Bundle\AdminBundle\Behat\Page\AdminPanel;

class FormContext extends AbstractContext
{
    /**
     * @When I change form field :field to value :value
     */
    public function iChangeFormFieldWithValue($field, $value): void
    {
        Assertion::notEq($this->getFormElement()->findField($field)->getValue(), $value);
        $this->getFormElement()->fillField($field, $value);
    }

    /**
     * @Given /^I should see form with following fields$/
     */
    public function iShouldSeeFormWithFollowingFields(TableNode $table): void
    {
        $form = $this->getFormElement();
        foreach ($table->getHash() as $fieldRow) {
            Assertion::true($form->hasField($fieldRow['Field name']));
        }
    }

    /**
     * @Given /^I press form "([^"]*)" button$/
     */
    public function iPressFormButton($button): void
    {
        $this->getFormElement()->pressButton($button);
    }

    /**
     * @Given I fill the form with values:
     */
    public function iFillFormFields(TableNode $table): void
    {
        $form = $this->getFormElement();
        foreach ($table->getHash() as $fieldRow) {
            $fieldName = $fieldRow['Field name'];
            $fieldValue = $fieldRow['Field value'];
            Assertion::true($form->hasField($fieldName));
            $field = $form->findField($fieldName);
            if ('checkbox' === $field->getAttribute('type')) {
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
    public function transformToNonEditableCollection($collectionNames): ?NodeElement
    {
        return $this->getPage(AdminPanel::class)->getNonEditableCollection($collectionNames);
    }

    /**
     * @Transform /^"([^"]*)" collection/
     * @Transform /collection "([^"]*)"/
     */
    public function transformToCollection($collectionNames): ?NodeElement
    {
        return $this->getPage(AdminPanel::class)->getCollection($collectionNames);
    }

    /**
     * @Given /^("[^"]*" collection) has (\d+) (element|elements)$/
     * @Then /^("[^"]*" collection) should have (\d+) (element|elements)$/
     * @Given /^(non-editable collection "[^"]*") has (\d+) (element|elements)$/
     * @Then /^(non-editable collection "[^"]*") should have (\d+) (element|elements)$/
     * @Then /^(removable-only collection "[^"]*") should have (\d+) (element|elements)$/
     */
    public function collectionShouldHaveElements(NodeElement $collection, $elementsCount): void
    {
        $elements = $collection->findAll('xpath', '/*/*[@class = "form-group"]');
        Assertion::eq(count($elements), $elementsCount);
    }

    /**
     * @Given /^(collection "[^"]*") should have "([^"]*)" button$/
     */
    public function collectionShouldHaveButton(NodeElement $collection, $buttonName): void
    {
        Assertion::notNull($collection->findButton($buttonName));
    }

    /**
     * @Then /^all buttons for adding and removing items in (non-editable collection "[^"]*") should be disabled$/
     */
    public function allCollectionButtonsDisabled(NodeElement $collection): void
    {
        $removeButtons = $collection->findAll('css', '.collection-remove');
        Assertion::notEq(count($removeButtons), 0);
        foreach ($removeButtons as $removeButton) {
            Assertion::true($removeButton->hasClass('disabled'));
        }
        $addButtons = $collection->findAll('css', '.collection-add');
        Assertion::notEq(count($addButtons), 0);
        foreach ($addButtons as $addButton) {
            Assertion::true($addButton->hasClass('disabled'));
        }
    }

    /**
     * @Then /^button for adding item in (removable-only collection "[^"]*") should be disabled$/
     */
    public function collectionAddButtonIsDisabled(NodeElement $collection): void
    {
        $addButtons = $collection->findAll('css', '.collection-add');
        Assertion::notEq(count($addButtons), 0);
        foreach ($addButtons as $addButton) {
            Assertion::true($addButton->hasClass('disabled'));
        }
    }

    /**
     * @Then /^buttons for removing items in (removable-only collection "[^"]*") should be enabled/
     */
    public function collectionRemoveButtonsAreEnabled(NodeElement $collection): void
    {
        $addButtons = $collection->findAll('css', '.collection-remove');
        Assertion::notEq(count($addButtons), 0);
        foreach ($addButtons as $addButton) {
            Assertion::false($addButton->hasClass('disabled'));
        }
    }

    /**
     * @When /^I press "([^"]*)" in (collection "[^"]*")$/
     */
    public function iPressInCollection($buttonName, NodeElement $collection): void
    {
        $collection
            ->find('xpath', '/*[contains(concat(" ",normalize-space(@class)," ")," collection-add ")]')
            ->press();
    }

    /**
     * @Given /^I fill "([^"]*)" with "([^"]*)" in (collection "[^"]*") at position (\d+)$/
     */
    public function iFillWithInCollectionAtPosition($fieldName, $fieldValue, NodeElement $collection, $position): void
    {
        $collectionRow = $collection->find('xpath', sprintf('/*/*[@class = "form-group"][%d]', $position));
        $collectionRow->fillField($fieldName, $fieldValue);
    }

    /**
     * @Given /^I remove (\w+) element in (collection "[^"]*")$/
     * @Given /^I remove (\w+) element in (removable-only collection "[^"]*")$/
     */
    public function iRemoveElementInCollection($index, NodeElement $collection): void
    {
        $collection->find('xpath', sprintf('/*/*[@class = "form-group"][%d]', $index))
             ->find('css', '.collection-remove')->click();
    }

    private function getFormElement(): NodeElement
    {
        return $this->getSession()->getPage()->find('css', 'Form');
    }
}
