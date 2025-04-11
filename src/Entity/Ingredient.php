<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, IngredientQuantity>
     */
    #[ORM\OneToMany(targetEntity: IngredientQuantity::class, mappedBy: 'ingredient', orphanRemoval: true)]
    private Collection $ingredientQuantities;

    public function __construct()
    {
        $this->ingredientQuantities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, IngredientQuantity>
     */
    public function getIngredientQuantities(): Collection
    {
        return $this->ingredientQuantities;
    }

    public function addIngredientQuantity(IngredientQuantity $ingredientQuantity): static
    {
        if (!$this->ingredientQuantities->contains($ingredientQuantity)) {
            $this->ingredientQuantities->add($ingredientQuantity);
            $ingredientQuantity->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientQuantity(IngredientQuantity $ingredientQuantity): static
    {
        if ($this->ingredientQuantities->removeElement($ingredientQuantity)) {
            // set the owning side to null (unless already changed)
            if ($ingredientQuantity->getIngredient() === $this) {
                $ingredientQuantity->setIngredient(null);
            }
        }

        return $this;
    }
}
