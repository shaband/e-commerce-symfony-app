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
        CategoryFactory::new()->child()->many(5)->create();
    }
}
