<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\ConcertRate;
use App\Form\ConcertRateType;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_concert_")
 */
class ConcertController extends AbstractController
{
    /**
     * @Route("/calendrier", name="agenda", methods={"GET"})
     */
    public function agenda(ConcertRepository $concertRepository): Response
    {
        return $this->render('admin/views/agenda.html.twig', [
            'not_validated_concerts' => $concertRepository->findBy(['isValidated' => false], ['date' => 'ASC']),
            'validated_concerts' => $concertRepository->findBy(['isValidated' => true], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/proposer-une-date-de-concert", name="add", methods={"GET", "POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        MailerInterface $mailer
    ): Response {
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // @phpstan-ignore-next-line
            $concert->setOwner($this->getUser());
            $entityManager->persist($concert);
            $entityManager->flush();
            $email = (new Email())
                ->from('souqjazzmachine@bigband.fr')
                ->subject('Nouvelle proposition de date de concert!')
                ->html($this->renderView('email/views/new_concert_email.html.twig', [
                    'concert' => $concert,
                ]))
            ;
            foreach ($userRepository->findAll() as $user) {
                // @phpstan-ignore-next-line
                $email->addTo($user->getEmail());
            }
            $mailer->send($email);

            $this->addFlash('success', 'Date de concert proposée, plus qu\'à attendre qque tout le monde ait voté!');

            return $this->redirectToRoute('admin_concert_agenda');
        }

        return $this->render('admin/views/concert_add.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/modifier-le-concert/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Concert $concert
    ): Response {
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Concert modifié!');
            return $this->redirectToRoute('admin_concert_agenda');
        }

        return $this->render('admin/views/concert_edit.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter-un-tarif/{id}", name="rate_add", methods={"GET", "POST"})
     */
    public function addRate(
        Request $request,
        EntityManagerInterface $entityManager,
        Concert $concert
    ): Response {
        $rate = new ConcertRate();
        $rate->setConcert($concert);
        $form = $this->createForm(ConcertRateType::class, $rate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rate);
            $entityManager->flush();
            $this->addFlash('success', 'Tarif ajouté!');
            return $this->redirectToRoute('admin_concert_edit', ['id' => $concert->getId()]);
        }

        return $this->render('admin/views/rate_add.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/modifier-le-tarif/{id}", name="rate_edit", methods={"GET", "POST"})
     */
    public function editRate(
        Request $request,
        EntityManagerInterface $entityManager,
        ConcertRate $rate
    ): Response {
        $form = $this->createForm(ConcertRateType::class, $rate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Tarif modifié!');
            // @phpstan-ignore-next-line
            return $this->redirectToRoute('admin_concert_edit', ['id' => $rate->getConcert()->getId()]);
        }

        return $this->render('admin/views/rate_edit.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/supprimer-le-tarif/{id}", name="rate_delete", methods={"POST"})
     */
    public function deleteRate(Request $request, EntityManagerInterface $entityManager, ConcertRate $rate): Response
    {
        // @phpstan-ignore-next-line
        if ($this->isCsrfTokenValid('delete' . $rate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rate);
            $entityManager->flush();
            $this->addFlash('warning', 'Tarif supprimé!');
        }
        // @phpstan-ignore-next-line
        return $this->redirectToRoute('admin_concert_edit', ['id' => $rate->getConcert()->getId()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/valider-le-concert/{id}", name="validate", methods={"POST"})
     */
    public function validate(
        EntityManagerInterface $entityManager,
        Concert $concert,
        UserRepository $userRepository,
        MailerInterface $mailer
    ): Response {
        $concert->setIsValidated(true);
        $entityManager->flush();
        $email = (new Email())
                ->from('souqjazzmachine@bigband.fr')
                ->subject('Date de concert validée!')
                ->html($this->renderView('email/views/validate_concert_email.html.twig', [
                    'concert' => $concert,
                ]))
            ;
        foreach ($userRepository->findAll() as $user) {
            // @phpstan-ignore-next-line
            $email->addTo($user->getEmail());
        }
            $mailer->send($email);
        $this->addFlash('success', 'Date de concert validée!');
        return $this->redirectToRoute('admin_concert_agenda');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/supprimer-le-concert/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Concert $concert): Response
    {
        // @phpstan-ignore-next-line
        if ($this->isCsrfTokenValid('delete' . $concert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($concert);
            $entityManager->flush();
            $this->addFlash('warning', 'Concert annulé!');
        }

        return $this->redirectToRoute('admin_concert_agenda');
    }
}
