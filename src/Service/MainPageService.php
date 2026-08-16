<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\SponsorsRepository;
use App\Repository\TestimonialRepository;

class MainPageService
{
    public function __construct(
        private TestimonialRepository $testimonialRepository,
        private CategoryRepository    $categoryRepository,
        private SponsorsRepository    $sponsorsRepository
    )
    {
    }

    public function getIndexPageData()
    {
        $sponsors = $this->sponsorsRepository->getActive();
        $testimonials = $this->testimonialRepository->getActive();
$categories=$this->categoryRepository->getParentCategories();
    }

}
