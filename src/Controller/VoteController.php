<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Entity\Concert;
use App\Entity\User;
use App\Repository\AvailabilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/", name="admin_")
 */
class VoteController extends AbstractController
{
    /**
     * @Route("/voter/{id}", name="vote", methods={"POST"})
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function vote(
        Request $request,
        EntityManagerInterface $entityManager,
        AvailabilityRepository $availabilityRepository,
        Concert $concert
    ): Response {
        $availibility = $availabilityRepository->findOneBy(
            ['concert' => $concert, 'voter' => $this->getUser()]
        ) ?? new Availability();

        /**
         * DISCUSS : surely there's a way to do all this vote interaction "properly" by following Symfony best practices
         * -> refactor later?
         */
        $vote = $request->request->get('vote');

        if ($vote === '0' || $vote === '1') {
            $vote = boolval($request->request->get('vote'));

            if (!$availabilityRepository->findOneBy(['concert' => $concert, 'voter' => $this->getUser()])) {
                $entityManager->persist($availibility);
            }

            /** @var User */
            $user = $this->getUser();
            $availibility->setVote($vote)->setVoter($user)->setConcert($concert);
            $entityManager->flush();

            $this->addFlash('success', 'Merci pour ton vote!');
        } else {
            $this->addFlash('warning', 'Vote invalide');
        }

        return $this->redirectToRoute((string)$request->request->get('route'));
    }
}
