<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $featured;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="product", orphanRemoval=true)
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(?bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }
}
