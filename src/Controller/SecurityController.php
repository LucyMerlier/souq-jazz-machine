<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use LogicException;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/nouvel-utilisateur", name="app_register")
     */
    public function createUser(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setRoles(['ROLE_USER'])
            // encode the plain password
                ->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        'machine'
                    )
                )
            ;

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('souqjazzmachine@bigband.fr', 'Souq Registration Bot'))
                    ->to($user->getEmail() ?? 'souqjazzmachine@bigband.fr')
                    ->subject('Confirmation de votre adresse email')
                    ->htmlTemplate('email/views/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verification-email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            // @phpstan-ignore-next-line
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('admin_home');
        }

        $this->addFlash(
            'success',
            'Votre adresse email a bien été vérifiée!
            Vous pouvez maintenant compléter ou modifier vos informations personnelles'
        );

        $this->addFlash(
            'danger',
            'Rappel : veuillez modifier votre mot de passe au plus vite'
        );

        return $this->redirectToRoute('admin_home');
    }
}
