<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Tag
{
    private ?int $id = null;
    private ?string $name = null;
    private ?News $news = null;
    /**
     * @var Collection<int,TagElement>
     */
    private Collection $elements;

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
     * @return Collection<int,TagElement>
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(TagElement $element): void
    {
        if (false === $this->elements->contains($element)) {
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
