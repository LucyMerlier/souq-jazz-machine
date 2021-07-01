<?php

namespace App\Controller;

use App\Repository\ConcertRepository;
use App\Repository\InstrumentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(ConcertRepository $concertRepository): Response
    {
        $unvotedConcerts = [];
        $notValidatedConcerts = $concertRepository->findBy(['isValidated' => false], ['date' => 'ASC']);

        foreach ($notValidatedConcerts as $concert) {
            $votes = $concert->getVotes();

            if ($votes->isEmpty()) {
                $unvotedConcerts[] = $concert;
            }

            foreach ($votes as $vote) {
                // @phpstan-ignore-next-line
                if (!$this->getUser()->getVotes()->contains($vote)) {
                    $unvotedConcerts[] = $concert;
                }
            }
        }

        return $this->render('admin/views/index.html.twig', [
            'unvoted_concerts' => $unvotedConcerts,
            'concert' => $concertRepository->findOneBy(['isValidated' => true], ['date' => 'ASC']),
        ]);
    }

    /**
     * @Route("/calendrier", name="concert_agenda", methods={"GET"})
     */
    public function agenda(ConcertRepository $concertRepository): Response
    {
        return $this->render('admin/views/agenda.html.twig', [
            'not_validated_concerts' => $concertRepository->findBy(['isValidated' => false], ['date' => 'ASC']),
            'validated_concerts' => $concertRepository->findBy(['isValidated' => true], ['date' => 'ASC']),
        ]);
    }

    /**
     * @Route("/tous-les-membres", name="members", methods={"GET"})
     */
    public function bandMembers(InstrumentRepository $instrumentRepository): Response
    {
        return $this->render('admin/views/user_index.html.twig', [
            'instruments' => $instrumentRepository->findAll(),
        ]);
    }
}
