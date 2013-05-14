<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud\Doctrine;

use FSi\Bundle\AdminBundle\Context\Crud\AbstractCrudEditContextBuilder;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Structure\Doctrine\AdminElementInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudEditContextBuilder extends AbstractCrudEditContextBuilder
{
    /**
     * {@inheritdoc}
     */
    public static function supports(ElementInterface $element)
    {
        return $element instanceof AdminElementInterface;
    }
}