<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form;

use FSi\Bundle\AdminBundle\Translatable\Form\Extractor\FormDataExtractor;
use Symfony\Component\Form\FormInterface;

final class TranslatableFormDataExtractor
{
    private FormDataExtractor $dataExtractor;

    public function __construct(FormDataExtractor $dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
    }

    public function extract(FormInterface $form): FormTranslatableData
    {
        $result = new FormTranslatableData(false, null);
        for ($parent = $form; null !== $parent; $parent = $parent->getParent()) {
            if (true === $parent->getConfig()->getInheritData()) {
                continue;
            }

            $extractedData = $this->dataExtractor->extract($parent);
            if (null === $extractedData) {
                continue;
            }

            $result = $extractedData;
            break;
        }

        return $result;
    }
}
