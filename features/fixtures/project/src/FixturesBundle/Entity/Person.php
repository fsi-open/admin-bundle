<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Person
{
    private ?int $id = null;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
