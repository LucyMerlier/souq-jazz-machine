<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\InstrumentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/mes-informations", name="show", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->render('admin/user/show.html.twig');
    }

    /**
     * @Route("/modifier-mes-informations", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_user_show');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_SUPERADMIN")
     * @Route("/donner-les-droits-admin/{id}", name="grant_admin", methods={"POST"})
     */
    public function grantAdmin(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        User $user
    ): Response {
        if ($this->isCsrfTokenValid('grant_admin' . $user->getId(), (string)$request->request->get('_token'))) {
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager->flush();

            /** @var string */
            $emailFrom = $this->getParameter('email_address');
            $email = (new Email())
                ->from($emailFrom)
                ->subject('Droits d\'admin')
                ->addTo((string)$user->getEmail())
                ->html($this->renderView('email/views/grant_admin_email.html.twig', [
                    'user' => $this->getUser(),
                ]))
            ;
            $mailer->send($email);

            $this->addFlash(
                'success',
                $user->getFirstname() . ' ' . $user->getLastname() . ' a désormais les droits d\'administration!'
            );
        }

        return $this->redirectToRoute('admin_members');
    }

    /**
     * @IsGranted("ROLE_SUPERADMIN")
     * @Route("/revoquer-les-droits-admin/{id}", name="revoke_admin", methods={"POST"})
     */
    public function revokeAdmin(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        User $user
    ): Response {
        if ($this->isCsrfTokenValid('revoke_admin' . $user->getId(), (string)$request->request->get('_token'))) {
            $user->setRoles(['ROLE_USER']);
            $entityManager->flush();

            /** @var string */
            $emailFrom = $this->getParameter('email_address');
            $email = (new Email())
                ->from($emailFrom)
                ->subject('Droits d\'admin')
                ->addTo((string)$user->getEmail())
                ->html($this->renderView('email/views/revoke_admin_email.html.twig', [
                    'user' => $this->getUser(),
                ]))
            ;
            $mailer->send($email);

            $this->addFlash(
                'success',
                $user->getFirstname() . ' ' . $user->getLastname() .
                ' n\'a désormais plus les droits d\'administration!'
            );
        }

        return $this->redirectToRoute('admin_members');
    }

    /**
     * @IsGranted("ROLE_SUPERADMIN")
     * @Route("/transmettre-les-droits-superadmin/{id}", name="grant_superadmin", methods={"POST"})
     */
    public function grantSuperAdmin(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        User $newSuperadmin
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'grant_superadmin' . $newSuperadmin->getId(),
                (string)$request->request->get('_token')
            )
        ) {
            /** @var User */
            $currentSuperadmin = $this->getUser();
            $currentSuperadmin->setRoles(['ROLE_ADMIN']);
            $newSuperadmin->setRoles(['ROLE_SUPERADMIN']);
            $entityManager->flush();

            /** @var string */
            $emailFrom = $this->getParameter('email_address');
            $email = (new Email())
                ->from($emailFrom)
                ->subject('Droits super-admin')
                ->addTo((string)$newSuperadmin->getEmail())
                ->html($this->renderView('email/views/grant_superadmin_email.html.twig', [
                    'user' => $currentSuperadmin,
                ]))
            ;
            $mailer->send($email);

            $this->addFlash(
                'success',
                'Tu as transmis tes droits super-admin à ' .
                $newSuperadmin->getFirstname() . ' ' . $newSuperadmin->getLastname() . '.' .
                ' Tu es désormais simple admin!'
            );
        }

        return $this->redirectToRoute('admin_members');
    }

    /**
     * @IsGranted("ROLE_SUPERADMIN")
     * @Route("/supprimer-utilisateur/{id}", name="delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        User $user
    ): Response {
        if ($security->isGranted('ROLE_SUPERADMIN') && $user === $this->getUser()) {
            $this->addFlash(
                'danger',
                'Tu dois d\'abord transmettre tes droits super-admin avant de supprimer ton compte!'
            );
            return $this->redirectToRoute('admin_user_show');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('warning', 'Utilisateur·ice supprimé·e!');
        }

        return $this->redirectToRoute('admin_members');
    }

    /**
     * @Route("/supprimer-mon-compte", name="delete_account", methods={"POST"})
     */
    public function deleteAccount(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        if ($security->isGranted('ROLE_SUPERADMIN')) {
            $this->addFlash(
                'danger',
                'Tu dois d\'abord transmettre tes droits super-admin avant de supprimer ton compte!'
            );
            return $this->redirectToRoute('admin_user_show');
        }

        /** @var User */
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->addFlash('primary', 'Ton compte a bien été supprimé, désolé·e·s de te voir partir :\'(');

        /** @var TokenStorage */
        $tokenStorage = $this->get('security.token_storage');
        $tokenStorage->setToken(null);

        return $this->redirectToRoute('showcase_home');
    }

    /**
     * @Route("/ajax-users/{instrument}/{query}", name="ajax")
     */
    public function getUsers(
        InstrumentRepository $instrumentRepository,
        UserRepository $userRepository,
        int $instrument = 0,
        string $query = ''
    ): Response {
        return $this->render('admin/user/components/_members_list.html.twig', [
            'users' => $userRepository->findByQuery(
                $query,
                $instrumentRepository->find($instrument) ?? null
            ),
        ]);
    }
}
