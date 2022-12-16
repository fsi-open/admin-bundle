<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\Component\Files\WebFile;
use Symfony\Component\Validator\Constraints as Assert;

class News
{
    private ?int $id = null;

    private ?string $title = null;

    private ?string $subtitle = null;

    private ?DateTimeInterface $date = null;

    private bool $visible = false;

    private ?DateTimeInterface $createdAt = null;

    private ?string $creatorEmail = null;

    private ?string $photoPath = null;

    private ?WebFile $photo = null;

    /**
     * @var Collection<int,Category>
     */
    private Collection $categories;

    /**
     * @var Collection<int,Tag>
     *
     * @Assert\Valid
     */
    private Collection $tags;

    /**
     * @var Collection<int,string>
     */
    private Collection $comments;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatorEmail(?string $creatorEmail): void
    {
        $this->creatorEmail = $creatorEmail;
    }

    public function getCreatorEmail(): ?string
    {
        return $this->creatorEmail;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setSubtitle(?string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function getPhoto(): ?WebFile
    {
        return $this->photo;
    }

    public function setPhoto(?WebFile $photo): void
    {
        $this->photo = $photo;
    }

    public function addCategory(Category $category): void
    {
        $this->categories->add($category);
    }

    public function removeCategory(Category $category): void
    {
        $this->categories->removeElement($category);
    }

    /**
     * @return array<Category>
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    /**
     * @return Collection<int,Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (false === $this->tags->contains($tag)) {
            $tag->setNews($this);
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        $tag->setNews(null);
        $this->tags->removeElement($tag);
    }

    /**
     * @param array<int,Tag> $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = new ArrayCollection($tags);
    }

    /**
     * @return Collection<int,string>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
}
