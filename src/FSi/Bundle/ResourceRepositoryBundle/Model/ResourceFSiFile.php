<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Model;

class ResourceFSiFile extends Resource
{
    /**
     * @var string
     */
    protected $fileKeyValue;

    /**
     * @var \FSi\DoctrineExtensions\Uploadable\File|null
     */
    protected $fileValue;

    /**
     * @param mixed $fileKeyValue
     */
    public function setFileKeyValue($fileKeyValue)
    {
        $this->fileKeyValue = $fileKeyValue;
    }

    /**
     * @return mixed
     */
    public function getFileKeyValue()
    {
        return $this->fileKeyValue;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\File|null $fileValue
     */
    public function setFileValue($fileValue)
    {
        $this->fileValue = $fileValue;
    }

    /**
     * @return \FSi\DoctrineExtensions\Uploadable\File|null
     */
    public function getFileValue()
    {
        return $this->fileValue;
    }
}
