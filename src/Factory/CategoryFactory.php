<?php

namespace App\Factory;

use App\Entity\Category;
use App\Enum\CategoryGroupEnum;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Category>
 */
final class CategoryFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    #[\Override]
    public static function class(): string
    {
        return Category::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->text(255),
            'is_active' => self::faker()->boolean(),
            'Category_group' => CategoryGroupEnum::Item,
            'description' => self::faker()->sentence(),
            'cover' => self::faker()->imageUrl(),
        ];
    }

    public function main(): self
    {
        return $this->with([
            'category_group' => CategoryGroupEnum::Main,
            'parent' => null,
        ]);
    }

    public function group(): self
    {
        return $this->with([
            'category_group' => CategoryGroupEnum::Group,
            'parent' => CategoryFactory::new()->main()->create(),
        ]);
    }

    public function item(): self
    {
        return $this->with([
            'category_group' => CategoryGroupEnum::Item,
            'parent' => CategoryFactory::new()->group()->create(),
        ]);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this;
    }
}
