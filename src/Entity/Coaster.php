<?php

namespace App\Entity;

use App\Repository\CoasterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoasterRepository::class)]
class Coaster
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxSpeed = null;

    #[ORM\Column(nullable: true)]
    private ?int $length = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxHeight = null;

    #[ORM\Column]
    private ?bool $operating = null;

    #[ORM\ManyToOne]
    private ?Park $Park = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $Categories;

    #[ORM\ManyToOne]
    private ?User $User = null;

    #[ORM\Column]
    private ?bool $public = null;

    public function __construct()
    {
        $this->Categories = new ArrayCollection();
    }

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

    public function getMaxSpeed(): ?int
    {
        return $this->maxSpeed;
    }

    public function setMaxSpeed(?int $maxSpeed): static
    {
        $this->maxSpeed = $maxSpeed;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getMaxHeight(): ?int
    {
        return $this->maxHeight;
    }

    public function setMaxHeight(?int $maxHeight): static
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    public function isOperating(): ?bool
    {
        return $this->operating;
    }

    public function setOperating(bool $operating): static
    {
        $this->operating = $operating;

        return $this;
    }

    public function getPark(): ?Park
    {
        return $this->Park;
    }

    public function setPark(?Park $Park): static
    {
        $this->Park = $Park;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->Categories->contains($category)) {
            $this->Categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->Categories->removeElement($category);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }
}
