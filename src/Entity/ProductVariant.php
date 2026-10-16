<?php

namespace App\Entity;

use App\Repository\ProductVariantRepository;
use BcMath\Number;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
class ProductVariant
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 100, unique: true, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(type: Types::NUMBER, precision: 10, scale: 2)]
    private ?Number $price = null;

    #[ORM\Column(type: Types::NUMBER, precision: 10, scale: 2, nullable: true)]
    private ?Number $discount = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $isActive = true;

    /**
     * @var Collection<int, AttributeValue>
     */
    #[ORM\ManyToMany(targetEntity: AttributeValue::class, inversedBy: 'productVariants')]
    #[ORM\JoinTable(name: 'product_variant_attribute_value')]
    private Collection $attributeValues;

    /**
     * @var Collection<int, ProductImage>
     */
    #[ORM\OneToMany(targetEntity: ProductImage::class, mappedBy: 'productVariant')]
    private Collection $productImages;

    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
        $this->productImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?Number
    {
        return $this->price;
    }

    public function setPrice(Number $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?Number
    {
        return $this->discount;
    }

    public function setDiscount(?Number $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, AttributeValue>
     */
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(AttributeValue $attributeValue): static
    {
        if (!$this->attributeValues->contains($attributeValue)) {
            $this->attributeValues->add($attributeValue);
        }

        return $this;
    }

    public function removeAttributeValue(AttributeValue $attributeValue): static
    {
        $this->attributeValues->removeElement($attributeValue);

        return $this;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): static
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages->add($productImage);
            $productImage->setProductVariant($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): static
    {
        if ($this->productImages->removeElement($productImage)) {
            if ($productImage->getProductVariant() === $this) {
                $productImage->setProductVariant(null);
            }
        }

        return $this;
    }


    public function __toString(): string
    {
        $options = array_map(
            static fn (AttributeValue $v) => $v->getValue(),
            $this->attributeValues->toArray()
        );

        return trim(($this->product?->getName() ?? '') . ' - ' . implode(' / ', $options), ' -');
    }
}
