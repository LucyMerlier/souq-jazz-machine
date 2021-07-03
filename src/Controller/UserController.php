<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('admin/views/user_show.html.twig');
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

        return $this->render('admin/views/user_edit.html.twig', [
            'form' => $form->createView(),
        ]);
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
