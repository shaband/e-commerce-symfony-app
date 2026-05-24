<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminAbstractController extends AbstractController
{

    const ADMIN_TEMPLATE_PATH = 'admin';

    public function siteRender(string $view, array $parameters = [], ?Response $response = null)
    {

        $this->render(view: static::ADMIN_TEMPLATE_PATH . '/' . $view, parameters: $parameters, response: $response);
    }
}
