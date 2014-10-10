<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="News", inversedBy="tags")
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param News $news
     */
    public function setNews(News $news = null)
    {
        $this->news = $news;
    }

    /**
     * @return TagElement[]|ArrayCollection
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param TagElement $element
     */
    public function addElement(TagElement $element)
    {
        if (!$this->elements->contains($element)) {
            $element->setTag($this);
            $this->elements->add($element);
        }
    }

    /**
     * @param TagElement $element
     */
    public function removeElement(TagElement $element)
    {
        $element->setNews(null);
        $this->elements->removeElement($element);
    }
}
