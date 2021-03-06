<?php

namespace App\Controller;

use App\DataClass\ApplyOffer;
use App\DataClass\ContactRequest;
use App\Entity\Instrument;
use App\Entity\Offer;
use App\Form\ApplyOfferType;
use App\Form\ContactRequestType;
use App\Repository\CatchphraseRepository;
use App\Repository\ConcertRepository;
use App\Repository\HistoryRepository;
use App\Repository\InstrumentRepository;
use App\Repository\LegalNoticeRepository;
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
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ShowcaseController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(
        CatchphraseRepository $catchphraseRepository,
        NewsArticleRepository $newsRepository,
        ConcertRepository $concertRepository
    ): Response {
        return $this->render('showcase/views/index.html.twig', [
            'catchphrase' => $catchphraseRepository->findOneBy([]),
            'news_articles' => $newsRepository->findBy([], ['createdAt' => 'DESC'], 6),
            'concert' => $concertRepository->findByFutureDate(1)[0] ?? null,
        ]);
    }

    /**
     * @Route("/le-big-band", name="about_us", methods={"GET"})
     */
    public function aboutUs(
        HistoryRepository $historyRepository,
        InstrumentRepository $instrumentRepository
    ): Response {
        return $this->render('showcase/views/about_us.html.twig', [
            'our_history' => $historyRepository->findOneBy([]),
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

        if ($request->query->get('bugReporting') === 'true') {
            $contact->setSubject('J\'ai trouv?? un bug sur votre site!');
            $contact->setMessage(
                'Apr??s avoir fait <telle??s action??s> sur <telle??s page??s>, j\'ai re??u le message d\'erreur suivant :
<message d\'erreur>'
            );
        }

        $form = $this->createForm(ContactRequestType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (is_null($form['honeypot']->getData())) {
                /** @var string */
                $emailTo = $this->getParameter('email_address');
                $email = (new Email())
                    ->from((string)$contact->getEmailAddress())
                    ->to($emailTo)
                    ->subject((string)$contact->getSubject())
                    ->html($this->renderView('email/views/contact_request_email.html.twig', ['contact' => $contact]))
                ;

                $mailer->send($email);
            }

            $this->addFlash(
                'success',
                'Votre message a bien ??t?? envoy??, le Souq\' vous r??pondra dans les plus brefs d??lais.'
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
            if (is_null($form['honeypot']->getData())) {
                /** @var Instrument */
                $instrument = $offer->getInstrument();

                /** @var string */
                $emailTo = $this->getParameter('email_address');
                $email = (new Email())
                    ->from((string)$apply->getEmailAddress())
                    ->to($emailTo)
                    ->subject(
                        'Quelqu\'un a r??pondu ?? l\'annonce pour le pupitre ' . $instrument->getName() . '!'
                    )
                    ->html($this->renderView('email/views/apply_email.html.twig', [
                        'apply' => $apply,
                        'offer' => $offer,
                    ]))
                ;

                $mailer->send($email);
            }

            $this->addFlash(
                'success',
                'Votre demande a bien ??t?? envoy??e,
                le Souq\' vous recontactera par email ou t??l??hone dans les plus brefs d??lais!'
            );

            return $this->redirectToRoute('showcase_home');
        }

        return $this->render('showcase/views/apply.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/mentions-legales", name="legal_notices")
     */
    public function legalNotices(LegalNoticeRepository $legalNoticeRepository): Response
    {
        return $this->render('showcase/views/legal_notices.html.twig', [
            'legal_notices' => $legalNoticeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/credits", name="credits")
     */
    public function credits(): Response
    {
        return $this->render('showcase/views/credits.html.twig');
    }

    public function toast(OfferRepository $offerRepository): Response
    {
        return $this->render('showcase/_toast.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }
}
