<?php

namespace App\Controller;

use App\DataClass\FilterAlbum;
use App\DataClass\FilterConcert;
use App\DataClass\FilterPartner;
use App\DataClass\FilterSong;
use App\DataClass\FilterUser;
use App\Entity\User;
use App\Form\FilterAlbumType;
use App\Form\FilterConcertType;
use App\Form\FilterPartnerType;
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
     * @Route("/concerts", name="concert_agenda", methods={"GET"})
     */
    public function agenda(
        Request $request,
        ConcertRepository $concertRepository
    ): Response {
        $filterConcert = new FilterConcert();
        $filterForm = $this->createForm(FilterConcertType::class, $filterConcert);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            switch ($filterConcert->getSort()) {
                case 'proposed':
                    $concerts = $concertRepository->findByIsValidated(false, ['date' => 'ASC']);
                    break;
                case 'future':
                    $concerts = $concertRepository->findByFutureDate();
                    break;
                case 'past':
                    $concerts = $concertRepository->findByPastDate();
                    break;
                default:
                    $concerts = $concertRepository->findBy([], ['date' => 'ASC']);
                    break;
            }
        } else {
            $concerts = $concertRepository->findBy([], ['date' => 'ASC']);
        }

        return $this->render('admin/concert/index.html.twig', [
            'filter_form' => $filterForm->createView(),
            'concerts' => $concerts,
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
            'filter_form' => $filterForm->createView(),
            'songs' => $songs,
        ]);
    }

    /**
     * @Route("/membres", name="members", methods={"GET"})
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
            'filter_form' => $filterForm->createView(),
            'users' => $users,
        ]);
    }

    /**
     * @Route("/contacts", name="partners", methods={"GET"})
     */
    public function partners(
        Request $request,
        PartnerRepository $partnerRepository
    ): Response {
        $filterPartner = new FilterPartner();
        $filterForm = $this->createForm(FilterPartnerType::class, $filterPartner);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $partners = $partnerRepository->findByQuery(
                $filterPartner->getQuery(),
                $filterPartner->getCategory() ?? null
            );
        } else {
            $partners = $partnerRepository->findBy([], ['name' => 'ASC']);
        }
        return $this->render('admin/partner/index.html.twig', [
            'filter_form' => $filterForm->createView(),
            'partners' => $partners,
        ]);
    }

    /**
     * @Route("/albums-photos", name="albums", methods={"GET"})
     */
    public function albums(
        Request $request,
        AlbumRepository $albumRepository
    ): Response {
        $filterAlbum = new FilterAlbum();
        $filterForm = $this->createForm(FilterAlbumType::class, $filterAlbum);
        $filterForm->handleRequest($request);

        $orderBy = null;

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            switch ($filterAlbum->getSort()) {
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

            $albums = $albumRepository->findByQuery($orderBy ?? ['createdAt' => 'DESC'], $filterAlbum->getQuery());
        } else {
            $albums = $albumRepository->findBy([], ['createdAt' => 'DESC']);
        }

        return $this->render('admin/album/index.html.twig', [
            'filter_form' => $filterForm->createView(),
            'albums' => $albums,
        ]);
    }
}
