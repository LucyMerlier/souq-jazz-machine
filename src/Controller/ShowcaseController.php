<?php

namespace App\Controller;

use App\DataClass\ContactRequest;
use App\Form\ContactRequestType;
use App\Repository\ConcertRepository;
use App\Repository\InstrumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="showcase_")
 */
class ShowcaseController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('showcase/views/index.html.twig');
    }

    /**
     * @Route("/le-big-band", name="about_us", methods={"GET"})
     */
    public function aboutUs(InstrumentRepository $instrumentRepository): Response
    {
        return $this->render('showcase/views/about_us.html.twig', [
            'instruments' => $instrumentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/calendrier", name="agenda", methods={"GET"})
     */
    public function agenda(ConcertRepository $concertRepository): Response
    {
        return $this->render('showcase/views/agenda.html.twig', [
            'concerts' => $concertRepository->findBy(['isValidated' => true], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/contactez-nous", name="contact", methods={"GET", "POST"})
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactRequest();
        $form = $this->createForm(ContactRequestType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($contact->getEmailAddress() ?? 'souqjazzmachine@bigband.fr')
                ->to('souqjazzmachine@bigband.fr')
                ->subject($contact->getSubject() ?? 'Demande d\'informations')
                ->html($this->renderView('email/views/contact_request_email.html.twig', ['contact' => $contact]))
            ;

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé, le Souq\' vous répondra dans les plus brefs délais.'
            );

            return $this->redirectToRoute('showcase_contact');
        }

        return $this->render('showcase/views/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
