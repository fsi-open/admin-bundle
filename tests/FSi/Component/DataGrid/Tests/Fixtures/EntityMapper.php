<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Fixtures;

class EntityMapper
{
    public $id;

    private $private_id;

    private $name;

    private $surname;

    private $collection;

    private $private_collection;

    private $ready;

    private $protected_ready;

    private $tags = array();

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    protected function setProtectedName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setPrivateId($id)
    {
        $this->private_id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function getSurname()
    {
        return $this->surname;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function hasCollection()
    {
        return isset($this->collection);
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    public function setPrivateCollection($collection)
    {
        $this->private_collection = $collection;
    }

    private function hasPrivateCollection()
    {
        return isset($this->privatecollection);
    }

    public function setReady($ready)
    {
        $this->ready = (boolean)$ready;
    }

    public function isReady()
    {
        return $this->ready;
    }

    public function setProtectedReady($ready)
    {
        $this->protected_ready = (boolean)$ready;
    }

    protected function isProtectedReady()
    {
        return $this->protected_ready;
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }

    public function getTags()
    {
        return $this->tags;
    }

    protected function addProtectedTag($tag)
    {
        $this->tags[] = $tag;
    }
}
