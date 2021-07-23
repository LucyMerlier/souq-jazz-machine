<?php

namespace App\DataClass;

use Symfony\Component\Validator\Constraints as Assert;

class FilterConcert
{
    public const CHOICES = [
        'Dates proposées' => 'proposed',
        'Dates à venir' => 'future',
        'Dates passés' => 'past',
    ];

    /**
     * @Assert\Choice(choices=self::CHOICES)
     */
    private ?string $sort = '';

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function setSort(?string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
