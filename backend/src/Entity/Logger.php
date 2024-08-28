<?php

namespace App\Entity;

use App\Repository\LoggerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LoggerRepository::class)]
class Logger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['logger:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['logger:read', 'logger:write'])]
    private ?\DateTimeInterface $event_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['logger:read', 'logger:write', 'driver:read'])]
    private ?Driver $driver = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['logger:read', 'logger:write', 'car:read'])]
    private ?Car $car = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['logger:read', 'logger:write'])]
    private ?string $text = null;

    public function __construct()
    {
        $this->event_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(\DateTimeInterface $event_date): static
    {
        $this->event_date = $event_date;

        return $this;
    }

    public function getDriver(): ?string
    {
        return $this->driver->__toString();
    }

    public function setDriver(?Driver $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getCar(): ?string
    {
        return $this->car->__toString();
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }
}
