<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FSi\Bundle\DoctrineExtensionsBundle\Validator\Constraints as UploadableAssert;
use FSi\DoctrineExtensions\Uploadable\Mapping\Annotation as Uploadable;
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
     * @ORM\Column(type="date", nullable=true)
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible;

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
     * @Uploadable\Uploadable(targetField="photo")
     */
    protected $photoKey;

    /**
     * @var \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     * @UploadableAssert\Image()
     */
    protected $photo;

    /**
     * @ORM\Column(type="array", name="categories");
     */
    protected $categories;

    /**
     * @var Tag[]
     *
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="news", cascade={"persist"}, orphanRemoval=true)
     */
    protected $tags;

    public function __construct()
    {
        $this->categories = array();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return News
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $creatorEmail
     */
    public function setCreatorEmail($creatorEmail)
    {
        $this->creatorEmail = $creatorEmail;
    }

    /**
     * @return mixed
     */
    public function getCreatorEmail()
    {
        return $this->creatorEmail;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $visible
     * @return News
     */
    public function setVisible($visible)
    {
        $this->visible = (boolean) $visible;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @return mixed
     */
    public function getPhotoKey()
    {
        return $this->photoKey;
    }

    /**
     * @param mixed $photoKey
     */
    public function setPhotoKey($photoKey)
    {
        $this->photoKey = $photoKey;
    }

    /**
     * @return \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return (array) $this->categories;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $tag->setNews($this);
            $this->tags->add($tag);
        }
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $tag->setNews(null);
        $this->tags->removeElement($tag);
    }

    public function setTags(array $tags)
    {
        $this->tags = new ArrayCollection($tags);
    }
}
