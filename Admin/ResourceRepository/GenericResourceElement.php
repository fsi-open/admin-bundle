<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericResourceElement extends AbstractElement implements Element
{
    public function getRoute(): string
    {
        return 'fsi_admin_resource';
    }

    public function getSuccessRoute(): string
    {
        return $this->getRoute();
    }

    public function getSuccessRouteParameters(): array
    {
        return $this->getRouteParameters();
    }

    abstract public function getKey(): string;

    public function getResourceFormOptions(): array
    {
        return [];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => null,
        ]);

        $resolver->setAllowedTypes('template', ['null', 'string']);
    }
}
