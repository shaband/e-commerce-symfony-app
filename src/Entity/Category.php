<?php

namespace App\Entity;

use App\Enum\CategoryGroupEnum;
use App\Enum\GenderEnum;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
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

    #[ORM\Column(name: 'category_group', enumType: CategoryGroupEnum::class, options: ['default' => CategoryGroupEnum::Main])]
    private CategoryGroupEnum $category_group = CategoryGroupEnum::Main;

    /**
     * @var Collection<int, HomeCategorySection>
     */
    #[ORM\OneToMany(targetEntity: HomeCategorySection::class, mappedBy: 'categroryID')]
    private Collection $homeCategorySections;


    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->homeCategorySections = new ArrayCollection();
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

    /**
     * @return Collection<int, HomeCategorySection>
     */
    public function getHomeCategorySections(): Collection
    {
        return $this->homeCategorySections;
    }

    public function addHomeCategorySection(HomeCategorySection $homeCategorySection): static
    {
        if (!$this->homeCategorySections->contains($homeCategorySection)) {
            $this->homeCategorySections->add($homeCategorySection);
            $homeCategorySection->setCategroryID($this);
        }

        return $this;
    }

    public function removeHomeCategorySection(HomeCategorySection $homeCategorySection): static
    {
        if ($this->homeCategorySections->removeElement($homeCategorySection)) {
            // set the owning side to null (unless already changed)
            if ($homeCategorySection->getCategroryID() === $this) {
                $homeCategorySection->setCategroryID(null);
            }
        }

        return $this;
    }


}
