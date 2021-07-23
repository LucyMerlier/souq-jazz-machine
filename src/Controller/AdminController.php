<?php

namespace App\Controller;

use App\DataClass\FilterSong;
use App\DataClass\FilterUser;
use App\Entity\User;
use App\Form\FilterSongType;
use App\Form\FilterUserType;
use App\Repository\AlbumRepository;
use App\Repository\ConcertRepository;
use App\Repository\PartnerRepository;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/partitions", name="songs", methods={"GET"})
     */
    public function songs(
        Request $request,
        SongRepository $songRepository
    ): Response {
        $filterSong = new FilterSong();
        $filterForm = $this->createForm(FilterSongType::class, $filterSong);
        $filterForm->handleRequest($request);

        $orderBy = null;

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            switch ($filterSong->getSort()) {
                case 'dateDescending':
                    $orderBy = ['createdAt' => 'DESC'];
                    break;
                case 'dateAscending':
                    $orderBy = ['createdAt' => 'ASC'];
                    break;
                case 'titleAscending':
                    $orderBy = ['title' => 'ASC'];
                    break;
                case 'titleDescending':
                    $orderBy = ['title' => 'DESC'];
                    break;
            }

            $songs = $songRepository->findByQuery($orderBy ?? ['title' => 'ASC'], $filterSong->getQuery());
        } else {
            $songs = $songRepository->findBy([], ['title' => 'ASC']);
        }

        return $this->render('admin/song/index.html.twig', [
            'filterForm' => $filterForm->createView(),
            'songs' => $songs,
        ]);
    }

    /**
     * @Route("/tous-les-membres", name="members", methods={"GET"})
     */
    public function bandMembers(
        Request $request,
        UserRepository $userRepository
    ): Response {
        $filterUser = new FilterUser();
        $filterForm = $this->createForm(FilterUserType::class, $filterUser);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $users = $userRepository->findByQuery($filterUser->getQuery(), $filterUser->getInstrument() ?? null);
        } else {
            $users = $userRepository->findAllOrderByInstrument();
        }

        return $this->render('admin/user/index.html.twig', [
            'filterForm' => $filterForm->createView(),
            'users' => $users,
        ]);
    }

    /**
     * @Route("/tous-les-partenaires", name="partners", methods={"GET"})
     */
    public function partners(PartnerRepository $partnerRepository): Response
    {
        return $this->render('admin/partner/index.html.twig', [
            'partners' => $partnerRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/albums-photos", name="albums", methods={"GET"})
     */
    public function albums(AlbumRepository $albumRepository): Response
    {
        return $this->render('admin/album/index.html.twig', [
            'albums' => $albumRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }
}
