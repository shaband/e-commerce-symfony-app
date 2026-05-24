<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminAbstractController extends AbstractController
{

    const SITE_TEMPLATE_PATH = 'site';

    public function siteRender(string $view, array $parameters = [], ?Response $response = null)
    {

        $this->render(view:static::SITE_TEMPLATE_PATH . '/' . $view,parameters:  $parameters,response:  $response);
    }
}
