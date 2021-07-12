<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Container3xN5XhP\getPartnerService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/supprimer-utilisateur/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('warning', 'Utilisateur supprimé!');
        }

        return $this->redirectToRoute('admin_members');
    }

    /**
     * @Route("/supprimer-mon-compte", name="delete_account", methods={"POST"})
     */
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager): Response
    {
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
}
