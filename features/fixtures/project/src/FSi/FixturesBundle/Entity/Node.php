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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="node")
 */
class Node
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @var Node
     *
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="children", cascade={"persist"})
     */
    protected $parent;

    /**
     * @var Node[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Node")
     */
    protected $children;

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
     * @return Collection|Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
