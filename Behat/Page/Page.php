<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Page;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\Page as BasePage;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Rize\UriTemplate;

abstract class Page extends BasePage
{
    public function getHeader(): string
    {
        return $this->getElement('page header')->getText();
    }

    public function getCollection(string $label): ?NodeElement
    {
        return $this->getDocument()->find('xpath', sprintf(
            '//div[@data-prototype]/ancestor::*[@class = "form-group"]/label[text() = "%s"]/..//div[@data-prototype]',
            $label
        ));
    }

    public function getNonEditableCollection(string $label): ?NodeElement
    {
        return $this->getDocument()->find('xpath', sprintf(
            '//div[@data-prototype-name]/ancestor::*[@class = "form-group"]/label[text() = "%s"]/..//'
                . 'div[@data-prototype-name]',
            $label
        ));
    }

    public function openWithoutVerifying(array $urlParameters = []): void
    {
        $url = $this->getUrl($urlParameters);
        $this->getDriver()->visit($url);
    }

    public function getStatusCode(): int
    {
        return $this->getDriver()->getStatusCode();
    }

    public function hasBatchActionsDropdown(): bool
    {
        return $this->getDocument()->has('css', 'select[data-datagrid-name]');
    }

    public function hasBatchAction(string $value): bool
    {
        $select = $this->getDocument()->find('css', 'select[data-datagrid-name]');

        return $select->has('css', sprintf('option:contains("%s")', $value));
    }

    public function pressBatchCheckboxInRow(int $rowIndex): void
    {
        $tr = $this->getDocument()
            ->find('xpath', sprintf('descendant-or-self::table/tbody/tr[position() = %d]', $rowIndex));
        $tr->find('css', 'input[type="checkbox"]')->check();
    }

    public function pressBatchActionConfirmationButton()
    {
        $this->getDocument()->find('css', 'button[data-datagrid-name]')->click();
    }

    public function selectBatchAction($action)
    {
        $this->getDocument()->find('css', 'select[data-datagrid-name]')->selectOption($action);
    }

    public function getColumnPosition(string $columnHeader): int
    {
        $headers = $this->getDocument()->findAll('css', 'th');
        foreach ($headers as $index => $header) {
            /** @var NodeElement $header */
            if (
                true === $header->has('css', 'span')
                && $header->find('css', 'span')->getText() === $columnHeader
            ) {
                return $index + 1;
            }
        }

        throw new UnexpectedPageException(sprintf('Can\'t find column %s', $columnHeader));
    }

    public function getCell(string $columnHeader, int $rowNumber): ?NodeElement
    {
        $columnPos = $this->getColumnPosition($columnHeader);

        return $this->getDocument()
            ->find('xpath', sprintf('descendant-or-self::table/tbody/tr[%d]/td[%d]', $rowNumber, $columnPos));
    }

    public function getPopover(): ?NodeElement
    {
        return $this->getDocument()->find('css', '.popover');
    }

    public function isOpen(array $urlParameters = []): bool
    {
        $this->verify($urlParameters);

        return true;
    }

    public function open(array $urlParameters = []): void
    {
        if (false === $this->getDriver()->isStarted()) {
            $this->getDriver()->start();
        }

        parent::open($urlParameters);
    }

    protected function verifyUrl(array $urlParameters = []): void
    {
        $uriTemplate = new UriTemplate();
        $expectedUri = $uriTemplate->expand($this->getUrl($urlParameters), $urlParameters);
        if (false === strpos($this->getDriver()->getCurrentUrl(), $expectedUri)) {
            throw new UnexpectedPageException(sprintf(
                'Expected to be on "%s" but found "%s" instead',
                $expectedUri,
                $this->getDriver()->getCurrentUrl()
            ));
        }
    }

    protected function getDefinedElements(): array
    {
        return [
            'page header' => '#page-header',
        ];
    }
}
