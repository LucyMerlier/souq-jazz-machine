<?php

namespace App\DataClass;

use App\Entity\Partner;
use Symfony\Component\Validator\Constraints as Assert;

class FilterPartner
{
    /**
     * @Assert\Choice(choices=Partner::CATEGORIES)
     */
    private ?string $category = null;

    /**
     * @Assert\Length(max=100)
     */
    private ?string $query = '';

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

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
