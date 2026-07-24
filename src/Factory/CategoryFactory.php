<?php

namespace App\Factory;

use App\Entity\Category;
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
            'is_parent' => false,
            'description' => self::faker()->sentence(),
            'cover' => self::faker()->imageUrl(),
        ];
    }

    public function parent(): self
    {
        return $this->with(['is_parent' => true]);
    }

    public function child(): self
    {
        return $this->with([
            'is_parent' => false,
            'parent'=>CategoryFactory::new()->parent()->create(),
        ]);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Category $category): void {})
            ;
    }
}
