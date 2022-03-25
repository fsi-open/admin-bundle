<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Subscriber
{
    private ?int $id = null;

    /**
     * @Assert\Email()
     */
    private ?string $email = null;

    private bool $active = false;

    private ?DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
