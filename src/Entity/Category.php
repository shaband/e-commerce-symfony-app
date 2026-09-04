<?php

namespace App\Entity;

use App\Enum\CategoryGroupEnum;
use App\Enum\GenderEnum;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[Vich\Uploadable]
class Category
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $is_active = true;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cover = null;
    #[Vich\UploadableField(mapping: 'category_images', fileNameProperty: 'cover',size: 'coverSize')]
    #[Assert\Image(
        maxSize: '5M',
        maxSizeMessage: 'The uploaded image is too large ({{ size }} {{ suffix }}). Allowed maximum is {{ limit }} {{ suffix }}.'
    )]
    private ?File $coverFile = null;
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $coverSize = null;
    #[ORM\Column(name: 'category_group', enumType: CategoryGroupEnum::class)]
    private CategoryGroupEnum $category_group = CategoryGroupEnum::Main;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $showChildrenInHome = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $showProductsInHome = null;



    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->Name ?? '';
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getParentName(): ?string
    {
        return $this->parent?->getName();
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCategoryGroup(): CategoryGroupEnum
    {
        return $this->category_group;
    }

    public function setCategoryGroup(CategoryGroupEnum $category_group): static
    {
        $this->category_group = $category_group;

        return $this;
    }

    public function setCoverFile(?File $coverFile = null): void
    {
        $this->coverFile = $coverFile;
        if ($coverFile !== null) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getCoverFile(): ?File
    {
        return $this->coverFile;
    }

    public function setCoverSize(?int $coverSize): void
    {
        $this->coverSize = $coverSize;
    }

    public function getCoverSize(): ?int
    {
        return $this->coverSize;
    }

    public function isShowChildrenInHome(): ?bool
    {
        return $this->showChildrenInHome;
    }

    public function setShowChildrenInHome(bool $showChildrenInHome): static
    {
        $this->showChildrenInHome = $showChildrenInHome;

        return $this;
    }

    public function isShowProductsInHome(): ?bool
    {
        return $this->showProductsInHome;
    }

    public function setShowProductsInHome(bool $showProductsInHome): static
    {
        $this->showProductsInHome = $showProductsInHome;

        return $this;
    }
}
