<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form\Extractor;

use FSi\Bundle\AdminBundle\Translatable\Form\FormTranslatableData;
use FSi\Component\Translatable\ConfigurationResolver;
use Symfony\Component\Form\FormInterface;

use function is_object;

final class FormDataClassExtractor implements FormDataExtractor
{
    private ConfigurationResolver $configurationResolver;

    public function __construct(ConfigurationResolver $configurationResolver)
    {
        $this->configurationResolver = $configurationResolver;
    }

    public function extract(FormInterface $form): ?FormTranslatableData
    {
        /** @var class-string<object>|null $class */
        $class = $form->getConfig()->getDataClass();
        if (null === $class) {
            return null;
        }

        if (false === $this->configurationResolver->isTranslatable($class)) {
            return null;
        }

        $data = $form->getData();
        if (null !== $data && false === is_object($data)) {
            $data = null;
        }

        return new FormTranslatableData(true, $data);
    }
}
