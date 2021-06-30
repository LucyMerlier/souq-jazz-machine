<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Entity\Concert;
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
        $vote = $request->request->get('vote');

        if ($vote === '0' || $vote === '1') {
            $vote = boolval($request->request->get('vote'));

            if (!$availabilityRepository->findOneBy(['concert' => $concert, 'voter' => $this->getUser()])) {
                $entityManager->persist($availibility);
            }

            // @phpstan-ignore-next-line
            $availibility->setVote($vote)->setVoter($this->getUser())->setConcert($concert);
            $entityManager->flush();

            $this->addFlash('success', 'Merci pour ton vote!');
        } else {
            $this->addFlash('warning', 'Vote invalide');
        }

        return $this->redirectToRoute('admin_agenda');
    }
}
