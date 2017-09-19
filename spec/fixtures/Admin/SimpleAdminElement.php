<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Admin\Element
 */
class SimpleAdminElement extends AbstractElement
{
    public function getId(): string
    {
        return 'simple_admin_element';
    }

    public function getRoute(): string
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
