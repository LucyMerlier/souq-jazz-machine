<?php

namespace App\Entity;

use App\Entity\Concert;
use App\Entity\User;
use App\Repository\AvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvailabilityRepository::class)
 */
class Availability
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $voter;

    /**
     * @ORM\ManyToOne(targetEntity=Concert::class, inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Concert $concert;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $vote;

    public function __serialize(): array
    {
        return [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoter(): ?User
    {
        return $this->voter;
    }

    public function setVoter(?User $voter): self
    {
        $this->voter = $voter;

        return $this;
    }

    public function getConcert(): ?Concert
    {
        return $this->concert;
    }

    public function setConcert(?Concert $concert): self
    {
        $this->concert = $concert;

        return $this;
    }

    public function getVote(): ?bool
    {
        return $this->vote;
    }

    public function setVote(bool $vote): self
    {
        $this->vote = $vote;

        return $this;
    }
}
