<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud\Doctrine;

use FSi\Bundle\AdminBundle\Context\Crud\AbstractCrudDeleteContextBuilder;
use FSi\Bundle\AdminBundle\Exception\MissingOptionException;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudDeleteContextBuilder extends AbstractCrudDeleteContextBuilder
{
    /**
     * {@inheritdoc}
     */
    public static function supports(ElementInterface $element)
    {
        if ($element instanceof AdminElementInterface) {
            if (!$element->getOption('allow_delete')) {
                throw new MissingOptionException(sprintf('Element "%s" does not allow deleting objects.', $element->getId()));
            }

            return true;
        }

        return false;
    }
}