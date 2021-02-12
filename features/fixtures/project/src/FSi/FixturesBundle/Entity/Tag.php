<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var News
     *
     * @ORM\ManyToOne(targetEntity="News", inversedBy="tags", cascade={"persist"})
     */
    protected $news;

    /**
     * @var ArrayCollection|TagElement[]
     *
     * @ORM\OneToMany(targetEntity="TagElement", mappedBy="tag", cascade={"persist"}, orphanRemoval=true)
     */
    protected $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getNews(): ?News
    {
        return $this->news;
    }

    public function setNews(?News $news): void
    {
        $this->news = $news;
    }

    /**
     * @return TagElement[]|Collection
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(TagElement $element): void
    {
        if (!$this->elements->contains($element)) {
            $element->setTag($this);
            $this->elements->add($element);
        }
    }

    public function removeElement(TagElement $element): void
    {
        $element->setTag(null);
        $this->elements->removeElement($element);
    }
}
