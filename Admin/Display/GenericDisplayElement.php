<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Display;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Display\Display;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericDisplayElement extends AbstractElement implements Element
{
    public function getRoute(): string
    {
        return 'fsi_admin_display';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => null,
        ]);

        $resolver->setAllowedTypes('template', ['null', 'string']);
    }

    public function createDisplay($data): Display
    {
        return $this->initDisplay($data);
    }

    /**
     * @param object $data
     * @return Display
     */
    abstract protected function initDisplay($data): Display;
}
