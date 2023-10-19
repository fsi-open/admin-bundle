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
use Symfony\Component\Form\FormInterface;

interface FormDataExtractor
{
    /**
     * @param FormInterface<FormInterface> $form
     */
    public function extract(FormInterface $form): ?FormTranslatableData;
}
