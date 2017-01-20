<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\FileHandler;

use FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException;

class SplFileInfoHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     */
    public function getContent($file)
    {
        if (!$this->supports($file)) {
            throw $this->generateNotSupportedException($file);
        }

        $level = error_reporting(0);
        $content = file_get_contents($file->getRealpath());
        error_reporting($level);
        if (false === $content) {
            $error = error_get_last();
            throw new RuntimeException($error['message']);
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getName($file)
    {
        if (!$this->supports($file)) {
            throw $this->generateNotSupportedException($file);
        }

        return basename($file->getRealpath());
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return $file instanceof \SplFileInfo;
    }
}
