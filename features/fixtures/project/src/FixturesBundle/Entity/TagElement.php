<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class TagElement
{
    private ?int $id = null;

    /**
     * @Assert\NotBlank
     */
    private ?string $name;

    private ?Tag $tag = null;

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

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): void
    {
        $this->tag = $tag;
    }
}
