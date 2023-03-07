<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\LocaleProviderAware;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Component\Translatable\LocaleProvider;

class LocaleProviderWorker implements Worker
{
    private LocaleProvider $localeProvider;

    public function __construct(LocaleProvider $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    public function mount(Element $element): void
    {
        if (true === $element instanceof LocaleProviderAware) {
            $element->setLocaleProvider($this->localeProvider);
        }
    }
}
