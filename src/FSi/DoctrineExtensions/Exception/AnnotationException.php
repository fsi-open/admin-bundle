<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Exception;

use Doctrine\Common\Annotations\AnnotationException as BaseAnnotationException;

class AnnotationException extends BaseAnnotationException implements ExceptionInterface
{
}
