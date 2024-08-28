<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['model:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['model:read', 'model:write'])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Brand::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'brand_id', referencedColumnName: 'id')]
    #[Groups(['model:read', 'model:write', 'brand:read'])]
    private Brand|null $brand = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
