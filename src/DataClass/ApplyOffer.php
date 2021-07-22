<?php

namespace App\DataClass;

use Symfony\Component\Validator\Constraints as Assert;

class ApplyOffer
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private ?string $firstname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private ?string $lastname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @Assert\Email()
     */
    private ?string $emailAddress;

    /**
     * @Assert\Length(max=50)
     */
    private ?string $phoneNumber;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=10000)
     */
    private ?string $message;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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
