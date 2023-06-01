<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form;

final class PropertyTranslatableData
{
    private bool $translatable;
    private ?DefaultTranslation $defaultTranslation;

    public function __construct(bool $translatable, ?DefaultTranslation $defaultTranslation)
    {
        $this->translatable = $translatable;
        $this->defaultTranslation = $defaultTranslation;
    }

    public function isTranslatable(): bool
    {
        return $this->translatable;
    }

    public function getDefaultTranslation(): ?DefaultTranslation
    {
        return $this->defaultTranslation;
    }
}
