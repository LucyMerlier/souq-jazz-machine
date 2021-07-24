<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConcertRepository::class)
 */
class Concert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today")
     * @Assert\NotBlank()
     */
    private ?DateTimeInterface $date;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(max=4000)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private ?string $city;

    /**
     * @ORM\OneToMany(targetEntity=ConcertRate::class, mappedBy="concert", orphanRemoval=true)
     */
    private Collection $rates;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isValidated = false;

    /**
     * @ORM\OneToMany(targetEntity=Availability::class, mappedBy="concert", orphanRemoval=true)
     */
    private Collection $votes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $owner;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|ConcertRate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(ConcertRate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setConcert($this);
        }

        return $this;
    }

    public function removeRate(ConcertRate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getConcert() === $this) {
                $rate->setConcert(null);
            }
        }

        return $this;
    }

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * @return Collection|Availability[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Availability $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setConcert($this);
        }

        return $this;
    }

    public function removeVote(Availability $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getConcert() === $this) {
                $vote->setConcert(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
