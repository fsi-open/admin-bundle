<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Display\Element;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function get_class;
use function sprintf;

class DisplayController extends ControllerAbstract
{
    public function displayAction(string $element, Request $request): Response
    {
        $elementObject = $this->getElement($element);
        if (false === $elementObject instanceof Element) {
            throw new NotFoundHttpException(sprintf(
                'Admin element with id "%s" should be of class "%s", but it is "%s".',
                $element,
                Element::class,
                get_class($elementObject)
            ));
        }

        return $this->handleRequest($elementObject, $request, 'fsi_admin_display');
    }
}
