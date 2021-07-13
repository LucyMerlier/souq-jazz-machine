<?php

namespace App\Entity;

use App\Entity\Concert;
use App\Repository\ConcertRateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConcertRateRepository::class)
 */
class ConcertRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Assert\Length(max=40)
     */
    private ?string $category;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\NotBlank()
     * @Assert\PositiveOrZero()
     */
    private ?float $price;

    /**
     * @ORM\ManyToOne(targetEntity=Concert::class, inversedBy="rates")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Concert $concert;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
}
