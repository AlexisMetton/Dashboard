<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Property::class, mappedBy="category")
     */
    private $id_categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categories;

    public function __construct()
    {
        $this->id_categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getIdCategorie(): Collection
    {
        return $this->id_categorie;
    }

    public function addIdCategorie(Property $idCategorie): self
    {
        if (!$this->id_categorie->contains($idCategorie)) {
            $this->id_categorie[] = $idCategorie;
            $idCategorie->setCategory($this);
        }

        return $this;
    }

    public function removeIdCategorie(Property $idCategorie): self
    {
        if ($this->id_categorie->removeElement($idCategorie)) {
            // set the owning side to null (unless already changed)
            if ($idCategorie->getCategory() === $this) {
                $idCategorie->setCategory(null);
            }
        }

        return $this;
    }

    public function getCategories(): ?string
    {
        return $this->categories;
    }

    public function setCategories(string $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
