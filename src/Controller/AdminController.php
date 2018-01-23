<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 *
 * @package App\Controller
 */
class AdminController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        if (null === $this->getUser()) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        return $this->render(
            'admin/index.html.twig'
        );
    }
}
