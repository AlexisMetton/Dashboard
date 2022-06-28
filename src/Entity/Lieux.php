<?php

namespace App\Entity;

use App\Repository\LieuxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LieuxRepository::class)
 */
class Lieux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?int
    {
        return $this->lieu;
    }

    public function setLieu(int $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getVente(): ?string
    {
        return $this->vente;
    }

    public function setVente(string $vente): self
    {
        $this->vente = $vente;

        return $this;
    }
}
