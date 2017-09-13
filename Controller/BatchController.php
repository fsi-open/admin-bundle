<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="\FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement")
     */
    public function batchAction(BatchElement $element, Request $request): Response
    {
        return $this->handleRequest($element, $request, 'fsi_admin_batch');
    }
}
