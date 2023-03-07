<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Component\Translatable\LocaleProvider;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormFactoryInterface;

class LocaleProviderWorkerSpec extends ObjectBehavior
{
    public function let(LocaleProvider $localeProvider): void
    {
        $this->beConstructedWith($localeProvider);
    }

    public function it_mount_locale_provider_to_elements_that_are_locale_provider_aware(
        GenericCRUDElement $element,
        LocaleProvider $localeProvider
    ): void {
        $element->setLocaleProvider($localeProvider)->shouldBeCalled();

        $this->mount($element);
    }

    public function it_does_not_mount_locale_provider_to_elements_that_are_not_locale_provider_aware(
        Element $element,
        LocaleProvider $localeProvider
    ): void {
        $this->mount($element);
    }
}
