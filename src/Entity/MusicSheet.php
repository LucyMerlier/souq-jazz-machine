<?php

namespace App\Entity;

use App\Entity\Instrument;
use App\Entity\Song;
use App\Repository\MusicSheetRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MusicSheetRepository::class)
 * @Vich\Uploadable
 */
class MusicSheet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Instrument::class, inversedBy="musicSheets")
     */
    private ?Instrument $instrument;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url = null;

    /**
     * @Vich\UploadableField(
     *      mapping="music_sheets",
     *      fileNameProperty="url",
     * )
     * @Assert\File(maxSize="2M", mimeTypes={"application/pdf", "application/x-pdf"})
     */
    private ?File $file = null;

    /**
     * @ORM\ManyToOne(targetEntity=Song::class, inversedBy="musicSheets")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Song $song;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $updatedAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $specification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function setInstrument(?Instrument $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getUrl(): ?string
    {
        return '/uploads/music-sheets/' . $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): self
    {
        $this->song = $song;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(?string $specification): self
    {
        $this->specification = $specification;

        return $this;
    }
}
