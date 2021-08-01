<?php

namespace App\Controller;

use App\DataClass\AdminMail;
use App\Form\AdminMailType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin", name="admin_")
 */
class MailController extends AbstractController
{
    /**
     * @Route("/envoyer-un-email", name="send_mail", methods={"GET", "POST"})
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function sendMail(
        Request $request,
        MailerInterface $mailer,
        UserRepository $userRepository
    ): Response {
        $adminMail = new AdminMail();
        $form = $this->createForm(AdminMailType::class, $adminMail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string */
            $emailFrom = $this->getParameter('email_address');

            $email = (new Email())
                ->from($emailFrom)
                ->subject((string)$adminMail->getSubject())
                ->html($this->renderView('email/views/admin_email.html.twig', [
                    'message' => $adminMail->getMessage(),
                ]))
            ;

            $recipients = [];

            switch ($adminMail->getRecipients()) {
                case 'all':
                    $recipients = $userRepository->findAll();
                    break;
                case 'Voix':
                    $recipients = $userRepository->findByInstrument((string)$adminMail->getRecipients());
                    break;
                case 'wind':
                    $recipients = $userRepository->findByCategory((string)$adminMail->getRecipients());
                    break;
                case 'Saxophone':
                    $recipients = $userRepository->findByInstrument((string)$adminMail->getRecipients());
                    break;
                case 'Trompette':
                    $recipients = $userRepository->findByInstrument((string)$adminMail->getRecipients());
                    break;
                case 'Trombone':
                    $recipients = $userRepository->findByInstrument((string)$adminMail->getRecipients());
                    break;
                case 'rhythm':
                    $recipients = $userRepository->findByCategory((string)$adminMail->getRecipients());
                    break;
            }

            foreach ($recipients as /** @var User */ $recipient) {
                $emailAddress = (string)$recipient->getEmail();
                $email->addTo($emailAddress);
            }
            $mailer->send($email);

            $this->addFlash('success', 'Le message a bien été envoyé');

            return $this->redirectToRoute('admin_send_mail');
        }

        return $this->render('admin/send_mail/send_mail.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
