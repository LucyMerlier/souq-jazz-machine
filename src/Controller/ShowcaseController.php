<?php

namespace App\Controller;

use App\DataClass\ApplyOffer;
use App\DataClass\ContactRequest;
use App\Entity\Instrument;
use App\Entity\Offer;
use App\Form\ApplyOfferType;
use App\Form\ContactRequestType;
use App\Repository\ConcertRepository;
use App\Repository\InstrumentRepository;
use App\Repository\NewsArticleRepository;
use App\Repository\OfferRepository;
use App\Repository\PictureRepository;
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
    public function index(NewsArticleRepository $newsRepository, ConcertRepository $concertRepository): Response
    {
        if (isset($_SESSION)) {
            var_dump($_SESSION);
        }
        return $this->render('showcase/views/index.html.twig', [
            'news_articles' => $newsRepository->findBy([], ['createdAt' => 'DESC'], 6),
            'concert' => $concertRepository->findByFutureDate(1)[0] ?? null,
        ]);
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
            'concerts' => $concertRepository->findByFutureDate() ?? null,
        ]);
    }

    /**
     * @Route("/galerie", name="gallery", methods={"GET"})
     */
    public function gallery(PictureRepository $pictureRepository): Response
    {
        return $this->render('showcase/views/gallery.html.twig', [
            'pictures' => $pictureRepository->findByIsVisible(true),
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
                ->from((string)$contact->getEmailAddress())
                ->to('souqjazzmachine@bigband.fr')
                ->subject((string)$contact->getSubject())
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

    /**
     * @Route("/repondre-a-une-annonce/{id}", name="apply_offer", methods={"GET", "POST"})
     */
    public function applyOffer(
        Request $request,
        MailerInterface $mailer,
        Offer $offer
    ): Response {
        $apply = new ApplyOffer();
        $form = $this->createForm(ApplyOfferType::class, $apply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Instrument */
            $instrument = $offer->getInstrument();
            $email = (new Email())
                ->from((string)$apply->getEmailAddress())
                ->to('souqjazzmachine@bigband.fr')
                ->subject(
                    'Quelqu\'un a répondu à l\'annonce pour le pupitre ' . $instrument->getName() . '!'
                )
                ->html($this->renderView('email/views/apply_email.html.twig', [
                    'apply' => $apply,
                    'offer' => $offer,
                ]))
            ;

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre demande a bien été envoyée,
                le Souq\' vous recontactera par email ou téléhone dans les plus brefs délais!'
            );

            return $this->redirectToRoute('showcase_home');
        }

        return $this->render('showcase/views/apply.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    public function toast(OfferRepository $offerRepository): Response
    {
        return $this->render('showcase/_toast.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }
}
