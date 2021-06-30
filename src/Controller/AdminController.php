<?php

namespace App\Controller;

use App\Repository\ConcertRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('admin/views/index.html.twig');
    }

    /**
     * @Route("/calendrier", name="agenda")
     */
    public function agenda(ConcertRepository $concertRepository): Response
    {
        return $this->render('admin/views/agenda.html.twig', [
            'not_validated_concerts' => $concertRepository->findBy(['isValidated' => false], ['date' => 'DESC']),
            'validated_concerts' => $concertRepository->findBy(['isValidated' => true], ['date' => 'DESC']),
        ]);
    }
}
