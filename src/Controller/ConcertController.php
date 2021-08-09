<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Entity\Concert;
use App\Entity\ConcertRate;
use App\Entity\User;
use App\Form\ConcertRateType;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use App\Repository\UserRepository;
use DateTime;
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
            /** @var User */
            $user = $this->getUser();
            $concert->setOwner($user->getPseudonym() ?? $user->getFirstname() . ' ' . $user->getLastname());
            $entityManager->persist($concert);

            $vote = new Availability();
            $vote->setConcert($concert);
            $vote->setVoter($user);
            $vote->setVote(true);
            $entityManager->persist($vote);

            $entityManager->flush();

            /** @var string */
            $emailFrom = $this->getParameter('email_address');
            $email = (new Email())
                ->from($emailFrom)
                ->subject('Nouvelle proposition de date de concert!')
                ->html($this->renderView('email/views/new_concert_email.html.twig', [
                    'concert' => $concert,
                ]))
            ;
            foreach ($userRepository->findAll() as /** @var User */ $user) {
                $emailAddress = (string)$user->getEmail();
                $email->addTo($emailAddress);
            }
            $mailer->send($email);

            $this->addFlash('success', 'Date de concert proposée, plus qu\'à attendre que tout le monde ait voté!');

            return $this->redirectToRoute('admin_concert_edit', ['id' => $concert->getId()]);
        }

        return $this->render('admin/concert/add.html.twig', [
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

        return $this->render('admin/concert/edit.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
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

        return $this->render('admin/concert/rate_add.html.twig', [
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

            /** @var Concert */
            $concert = $rate->getConcert();
            return $this->redirectToRoute('admin_concert_edit', ['id' => $concert->getId()]);
        }

        return $this->render('admin/concert/rate_edit.html.twig', [
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
        if ($this->isCsrfTokenValid('delete' . $rate->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($rate);
            $entityManager->flush();
            $this->addFlash('warning', 'Tarif supprimé!');
        }

        /** @var Concert */
        $concert = $rate->getConcert();
        return $this->redirectToRoute('admin_concert_edit', ['id' => $concert->getId()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/valider-le-concert/{id}", name="validate", methods={"POST"})
     */
    public function validate(
        EntityManagerInterface $entityManager,
        Concert $concert,
        UserRepository $userRepository,
        MailerInterface $mailer,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('validate' . $concert->getId(), (string)$request->request->get('_token'))) {
            $concert->setIsValidated(true);
            $entityManager->flush();

            /** @var string */
            $emailFrom = $this->getParameter('email_address');
            $email = (new Email())
                    ->from($emailFrom)
                    ->subject('Date de concert validée!')
                    ->html($this->renderView('email/views/validate_concert_email.html.twig', [
                        'concert' => $concert,
                    ]))
                ;
            foreach ($userRepository->findAll() as /** @var User */ $user) {
                $emailAddress = (string)$user->getEmail();
                $email->addTo($emailAddress);
            }
            $mailer->send($email);
            $this->addFlash('success', 'Date de concert validée!');
        }

        return $this->redirectToRoute((string)$request->request->get('route'));
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/invalider-le-concert/{id}", name="invalidate", methods={"POST"})
     */
    public function invalidate(
        EntityManagerInterface $entityManager,
        Concert $concert,
        UserRepository $userRepository,
        MailerInterface $mailer,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('invalidate' . $concert->getId(), (string)$request->request->get('_token'))) {
            $concert->setIsValidated(false);
            $entityManager->flush();

            /** @var string */
            $emailFrom = $this->getParameter('email_address');
            $email = (new Email())
                    ->from($emailFrom)
                    ->subject('Date de concert invalidée!')
                    ->html($this->renderView('email/views/invalidate_concert_email.html.twig', [
                        'concert' => $concert,
                    ]))
                ;
            foreach ($userRepository->findAll() as /** @var User */ $user) {
                $emailAddress = (string)$user->getEmail();
                $email->addTo($emailAddress);
            }
            $mailer->send($email);
            $this->addFlash('success', 'Date de concert invalidée!');
        }

        return $this->redirectToRoute((string)$request->request->get('route'));
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/supprimer-le-concert/{id}", name="delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        Concert $concert,
        MailerInterface $mailer,
        UserRepository $userRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $concert->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($concert);
            $entityManager->flush();

            if ($concert->getIsValidated() && $concert->getDate() > new DateTime('now')) {
                /** @var string */
                $emailFrom = $this->getParameter('email_address');
                $email = (new Email())
                    ->from($emailFrom)
                    ->subject('Date de concert annulée!')
                    ->html($this->renderView('email/views/deleted_concert_email.html.twig', [
                        'concert' => $concert,
                    ]))
                ;
                foreach ($userRepository->findAll() as /** @var User */ $user) {
                    $emailAddress = (string)$user->getEmail();
                    $email->addTo($emailAddress);
                }
                $mailer->send($email);
                $this->addFlash(
                    'warning',
                    'Concert annulé! Prends le temps de prévenir les visiteurs du site en ajoutant une actu!'
                );
                return $this->redirectToRoute('admin_news_add');
            }

            $this->addFlash('warning', 'Concert supprimé!');
        }

        return $this->redirectToRoute('admin_concert_agenda');
    }

    /**
     * @Route("/ajax-concerts/{filter}", name="ajax")
     */
    public function getConcerts(
        ConcertRepository $concertRepository,
        string $filter = ''
    ): Response {
        $concerts = [];

        switch ($filter) {
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

        return $this->render('admin/concert/components/_concerts_list.html.twig', [
            'concerts' => $concerts,
        ]);
    }
}
