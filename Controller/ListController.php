<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends ControllerAbstract
{
    /**
     * @param ListElement<array<string, mixed>|object> $element
     *
     * @ParamConverter("element", class="\FSi\Bundle\AdminBundle\Admin\CRUD\ListElement")
     */
    public function listAction(ListElement $element, Request $request): Response
    {
        return $this->handleRequest($element, $request, 'fsi_admin_list');
    }
}
