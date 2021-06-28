<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactRequestType;
use App\DataClass\ContactRequest;
use App\Repository\UserRepository;

/**
 * @Route(name="showcase_")
 */
class ShowcaseController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('showcase/views/index.html.twig');
    }

    /**
     * @Route("/le-big-band", name="about_us")
     */
    public function aboutUs(UserRepository $userRepository): Response
    {
        return $this->render('showcase/views/about_us.html.twig', [
            'band_members' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/contactez-nous", name="contact")
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
                ->html($this->renderView('showcase/views/contact_request_email.html.twig', ['contact' => $contact]))
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
