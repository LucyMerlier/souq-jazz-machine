<?php

namespace App\DataClass;

use Symfony\Component\Validator\Constraints as Assert;

class FilterSong
{
    public const CHOICES = [
        'Les + récents' => 'dateDescending',
        'Les + anciens' => 'dateAscending',
        'Alphabétique' => 'titleAscending',
        'Anti-alphabétique' => 'titleDescending',
    ];

    /**
     * @Assert\Choice(choices=self::CHOICES)
     */
    private ?string $sort = '';

    /**
     * @Assert\Length(max=100)
     */
    private ?string $query = '';

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function setSort(?string $sort): self
    {
        $this->sort = $sort;

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
