<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Exception;

/**
 * Thrown when option does't exists.
 *
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class MissingOptionExteption extends \InvalidArgumentException implements ExceptionInterface
{
}