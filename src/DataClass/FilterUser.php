<?php

namespace App\DataClass;

use App\Entity\Instrument;
use Symfony\Component\Validator\Constraints as Assert;

class FilterUser
{
    private ?Instrument $instrument = null;

    /**
     * @Assert\Length(max=100)
     */
    private ?string $query = '';

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function setInstrument(?Instrument $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }
}
