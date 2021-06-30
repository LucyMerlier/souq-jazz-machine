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
use Doctrine\ORM\EntityManagerInterface;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Form\ChangePasswordType;
use App\Entity\User;
use App\DataClass\ChangePassword;

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
     * @Route("/admin/nouvel-utilisateur", name="app_register")
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

            $this->addFlash('success', 'Un email de confirmation a été envoyé au nouveau / à la nouvelle venu·e! :D');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/verification-email", name="app_verify_email")
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

            return $this->redirectToRoute('admin_change_password');
        }

        $this->addFlash(
            'success',
            'Ton adresse email a bien été vérifiée!
            Tu peux maintenant compléter ou modifier tes informations personnelles :)'
        );

        $this->addFlash(
            'danger',
            'Modifie ton mot de passe au plus vite!'
        );

        return $this->redirectToRoute('admin_change_password');
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/admin/modifier-mon-mot-de-passe", name="admin_change_password")
     */
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $changePassword = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // @phpstan-ignore-next-line
            $this->getUser()->setPassword(
                $passwordHasher->hashPassword(
                    // @phpstan-ignore-next-line
                    $this->getUser(),
                    $form->get('newPassword')->getData()
                )
            );

            $entityManager->flush();

            $this->addFlash('success', 'Ton mot de passe a bien été modifié!');

            return $this->redirectToRoute('admin_user_edit');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
