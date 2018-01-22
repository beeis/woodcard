<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 *
 * @package App\Controller
 */
class MainController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'main/index.html.twig'
        );
    }
}
