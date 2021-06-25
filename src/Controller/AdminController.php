<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="home")
     */
    public function index(): Response
    {
        return $this->render('admin/views/index.html.twig');
    }
}
