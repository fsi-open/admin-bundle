<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FSi\Component\Files\WebFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class News
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $subtitle;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible = false;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="text", name="creator_email")
     */
    protected $creatorEmail;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    protected $photoPath;

    /**
     * @var WebFile
     */
    protected $photo;

    /**
     * @var Category[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Category")
     */
    protected $categories;

    /**
     * @var Tag[]|Collection
     *
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="news", cascade={"persist"}, orphanRemoval=true)
     */
    protected $tags;

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

    /**
     * @return WebFile
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo $photo
     */
    public function setPhoto($photo): void
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
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    /**
     * @return Tag[]|Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $tag->setNews($this);
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        $tag->setNews(null);
        $this->tags->removeElement($tag);
    }

    public function setTags(array $tags): void
    {
        $this->tags = new ArrayCollection($tags);
    }
}
