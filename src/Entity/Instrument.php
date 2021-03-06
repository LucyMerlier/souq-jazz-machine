<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InstrumentRepository::class)
 */
class Instrument
{
    public const CATEGORIES = [
        'Vent' => 'wind',
        'Rythmique' => 'rhythm',
        'Autre' => 'other',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Choice(choices=self::CATEGORIES)
     */
    private ?string $category;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="instrument")
     */
    private Collection $players;

    /**
     * @ORM\OneToMany(targetEntity=MusicSheet::class, mappedBy="instrument", orphanRemoval=true)
     */
    private Collection $musicSheets;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->musicSheets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(User $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setInstrument($this);
        }

        return $this;
    }

    public function removePlayer(User $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getInstrument() === $this) {
                $player->setInstrument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MusicSheet[]
     */
    public function getMusicSheets(): Collection
    {
        return $this->musicSheets;
    }

    public function addMusicSheet(MusicSheet $musicSheet): self
    {
        if (!$this->musicSheets->contains($musicSheet)) {
            $this->musicSheets[] = $musicSheet;
            $musicSheet->setInstrument($this);
        }

        return $this;
    }

    public function removeMusicSheet(MusicSheet $musicSheet): self
    {
        if ($this->musicSheets->removeElement($musicSheet)) {
            // set the owning side to null (unless already changed)
            if ($musicSheet->getInstrument() === $this) {
                $musicSheet->setInstrument(null);
            }
        }

        return $this;
    }
}
