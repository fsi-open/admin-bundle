<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Bundle\AdminBundle\Admin\RequestStackAware;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackAwareElement extends SimpleAdminElement implements RequestStackAware
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
