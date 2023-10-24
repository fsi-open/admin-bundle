<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\Element as AdminElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element as AdminDoctrineElement;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @property ManagerInterface $manager
 */
trait DataIndexerElementFinder
{
    /**
     * @return DataIndexerElement<object>&AdminDoctrineElement<object>
     * @throws NotFoundHttpException
     */
    private function getElement(string $elementId): DataIndexerElement
    {
        if (false === $this->manager->hasElement($elementId)) {
            throw new NotFoundHttpException("Admin element with id {$elementId} does not exist.");
        }

        /** @var AdminElement|(DataIndexerElement<object>&AdminDoctrineElement<object>) $elementObject */
        $elementObject = $this->manager->getElement($elementId);

        if (
            false === $elementObject instanceof DataIndexerElement
            || false === $elementObject instanceof AdminDoctrineElement
        ) {
            throw new NotFoundHttpException(sprintf(
                'Admin element with id "%s" should implement interfaces "%s" and "%s".',
                $elementId,
                DataIndexerElement::class,
                AdminElement::class
            ));
        }

        return $elementObject;
    }
}
