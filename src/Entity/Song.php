<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\SongRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $composer;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $arranger;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $owner;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=MusicSheet::class, mappedBy="song", orphanRemoval=true)
     */
    private Collection $musicSheets;

    public function __construct()
    {
        $this->musicSheets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getComposer(): ?string
    {
        return $this->composer;
    }

    public function setComposer(?string $composer): self
    {
        $this->composer = $composer;

        return $this;
    }

    public function getArranger(): ?string
    {
        return $this->arranger;
    }

    public function setArranger(?string $arranger): self
    {
        $this->arranger = $arranger;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
            $musicSheet->setSong($this);
        }

        return $this;
    }

    public function removeMusicSheet(MusicSheet $musicSheet): self
    {
        if ($this->musicSheets->removeElement($musicSheet)) {
            // set the owning side to null (unless already changed)
            if ($musicSheet->getSong() === $this) {
                $musicSheet->setSong(null);
            }
        }

        return $this;
    }
}
