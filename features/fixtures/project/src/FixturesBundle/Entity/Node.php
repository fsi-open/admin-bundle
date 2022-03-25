<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Node
{
    private ?int $id = null;

    private ?string $title = null;

    private Node $parent;

    /**
     * @var Collection<int,Node>
     */
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getParent(): Node
    {
        return $this->parent;
    }

    public function setParent(Node $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection<int,Node>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }
}
