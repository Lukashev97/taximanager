<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:read', 'car:write'])]
    private ?string $car_number = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['car:read', 'car:write', 'model:read'])]
    private ?Model $model = null;

    #[ORM\ManyToOne(targetEntity: Driver::class, cascade: ['persist', 'remove'])]
    #[Groups(['car:read', 'driver:read'])]
    private ?Driver $driver = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarNumber(): ?string
    {
        return $this->car_number;
    }

    public function setCarNumber(string $car_number): static
    {
        $this->car_number = $car_number;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getDriver(): ?string
    {
        return $this->driver ? $this->driver->getName() : null;
    }

    public function setDriver(?Driver $driver): static
    {
        // unset the owning side of the relation if necessary
        if ($driver === null && $this->driver !== null) {
            $this->driver->setCar(null);
        }

        // set the owning side of the relation if necessary
        if ($driver !== null && $driver->getCar() !== $this) {
            $driver->setCar($this);
        }

        $this->driver = $driver;

        return $this;
    }

    public function __toString(): string
    {
        return (string) 'Id: ' . $this->getId() . ' , Car Number: ' . $this->getCarNumber();
    }
}
