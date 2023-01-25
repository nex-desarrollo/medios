<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $CIF = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $provincia = null;

    #[ORM\Column]
    private ?bool $condicioneslegales = null;

    #[ORM\Column(length: 255)]
    private ?string $created = null;

    #[ORM\Column(length: 255)]
    private ?string $updated = null;

    #[ORM\Column]
    private ?bool $estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCIF(): ?string
    {
        return $this->CIF;
    }

    public function setCIF(string $CIF): self
    {
        $this->CIF = $CIF;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function isCondicioneslegales(): ?bool
    {
        return $this->condicioneslegales;
    }

    public function setCondicioneslegales(bool $condicioneslegales): self
    {
        $this->condicioneslegales = $condicioneslegales;

        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated()
    {
        $this->created = date("d-m-Y H:i:s");
        return $this;
    }

    public function setUpdated()
    {
        $this->updated = date("d-m-Y H:i:s");
        return $this;
    }

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(bool $estado): self {
        $this->estado = $estado;
        return $this;
    }
}
