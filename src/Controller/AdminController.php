<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ConcertRepository;
use App\Repository\InstrumentRepository;
use App\Repository\SongRepository;
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
        // DISCUSS : surely there's a simpler way to do that, maybe just by using a custom query -> refactor later?
        $unvotedConcerts = [];

        $notValidatedConcerts = $concertRepository->findBy(['isValidated' => false], ['date' => 'ASC']);

        foreach ($notValidatedConcerts as $concert) {
            $votes = $concert->getVotes();

            if ($votes->isEmpty()) {
                $unvotedConcerts[] = $concert;
            } else {
                $hasVoted = false;
                foreach ($votes as $vote) {
                    /** @var User */
                    $user = $this->getUser();
                    if ($user->getVotes()->contains($vote)) {
                        $hasVoted = true;
                    }
                }
                if (!$hasVoted) {
                    $unvotedConcerts[] = $concert;
                }
            }
        }

        return $this->render('admin/index.html.twig', [
            'unvoted_concerts' => $unvotedConcerts,
            'concert' => $concertRepository->findByFutureDate(1)[0],
        ]);
    }

    /**
     * @Route("/partitions", name="songs", methods={"GET"})
     */
    public function songs(SongRepository $songRepository): Response
    {
        return $this->render('admin/song/index.html.twig', [
            'songs' => $songRepository->findAll(),
        ]);
    }

    /**
     * @Route("/calendrier", name="concert_agenda", methods={"GET"})
     */
    public function agenda(ConcertRepository $concertRepository): Response
    {
        return $this->render('admin/concert/index.html.twig', [
            'not_validated_concerts' => $concertRepository->findBy(['isValidated' => false], ['date' => 'ASC']),
            'future_concerts' => $concertRepository->findByFutureDate(),
            'past_concerts' => $concertRepository->findByPastDate(),
        ]);
    }

    /**
     * @Route("/tous-les-membres", name="members", methods={"GET"})
     */
    public function bandMembers(InstrumentRepository $instrumentRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'instruments' => $instrumentRepository->findAll(),
        ]);
    }
}
