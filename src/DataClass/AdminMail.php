<?php

namespace App\DataClass;

use Symfony\Component\Validator\Constraints as Assert;

class AdminMail
{
    public const RECIPIENTS_CATEGORIES = [
        'Tutti' => 'all',
        'Voix' => 'Voix',
        'Vents' => 'wind',
        'Saxophones' => 'Saxophone',
        'Trompettes' => 'Trompette',
        'Trombones' => 'Trombone',
        'Rythmique' => 'rhythm',
    ];

    /**
     * @Assert\NotBlank()
     * @Assert\Choice(choices=self::RECIPIENTS_CATEGORIES)
     */
    private ?string $recipients;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     */
    private ?string $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=10000)
     */
    private ?string $message;

    public function getRecipients(): ?string
    {
        return $this->recipients;
    }

    public function setRecipients(?string $recipients): self
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
