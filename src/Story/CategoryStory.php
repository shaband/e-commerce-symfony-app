<?php

namespace App\Story;

use App\Factory\CategoryFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture('category')]
final class CategoryStory extends Story
{
    public function build(): void
    {
        CategoryFactory::new()->item()->many(3)->create();
        CategoryFactory::new()->group()->many(3)->create();
        CategoryFactory::new()->main()->many(3)->create();
    }
}
