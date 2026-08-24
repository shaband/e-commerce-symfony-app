<?php

namespace App\Controller\Site;

use App\Repository\SliderRepository;
use App\Repository\SponsorRepository;
use App\Repository\TestimonialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(
        SliderRepository $sliderRepository,
        TestimonialRepository $testimonialRepository,
        SponsorRepository $sponsorsRepository
    ): Response
    {
        return $this->render('site/index/index.html.twig', [
            'controller_name' => 'IndexController',
            'sliders' => $sliderRepository->findAllActive(),
            'testimonials' => $testimonialRepository->findAllActive(),
            'sponsors' => $sponsorsRepository->findAllActive(),
        ]);
    }
}
