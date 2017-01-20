<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class News
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $short_content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="time")
     */
    private $create_time;

    /**
     * @ORM\ManyToOne(targetEntity="FSi\Component\DataSource\Tests\Fixtures\Category", inversedBy="news")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="FSi\Component\DataSource\Tests\Fixtures\Category")
     */
    private $category2;

    /**
     * @ORM\ManyToMany(targetEntity="FSi\Component\DataSource\Tests\Fixtures\Group")
     */
    private $groups;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author.
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set short content.
     *
     * @param string $shortContent
     */
    public function setShortContent($shortContent)
    {
        $this->short_content = $shortContent;
    }

    /**
     * Get short content.
     *
     * @return string
     */
    public function getShortContent()
    {
        return $this->short_content;
    }

    /**
     * Set content.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set create date.
     *
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;
    }

    /**
     * Get create date.
     *
     * @return \DateTime
     */
    public function getCreate_Date()
    {
        return $this->create_date;
    }

    /**
     * Set create time.
     *
     * @param \DateTime $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;
    }

    /**
     * Get create time.
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set category.
     *
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get category.
     *
     * @return null|Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category2.
     *
     * @param Category $category
     */
    public function setCategory2(Category $category2)
    {
        $this->category2 = $category2;
    }

    /**
     * Get category2.
     *
     * @return null|Category
     */
    public function getCategory2()
    {
        return $this->category2;
    }

    /**
     * Get groups.
     *
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set tags.
     *
     * @param string $tags
    */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get tags.
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active = true)
    {
        $this->active = $active;
    }

    /**
     * Is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }
}
