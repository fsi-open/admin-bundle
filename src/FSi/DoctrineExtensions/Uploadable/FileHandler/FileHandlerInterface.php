<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\FileHandler;

interface FileHandlerInterface
{
    /**
     * Check if handler can handle given $file.
     *
     * @param $file
     * @return bool
     */
    public function supports($file);

    /**
     * Get name of resource, that will be a part of its key.
     *
     * This can be for example base name of file path.
     *
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException when resource not supported
     * @param mixed $file
     * @return string
     */
    public function getName($file);

    /**
     * Method must return instance of FSi\DoctrineExtensions\Uploadable\File or null,
     * if can't handle given resource.
     *
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException when resource not supported
     * @param mixed $file
     * @return string
     */
    public function getContent($file);
}
