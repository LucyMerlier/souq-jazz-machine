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
        return $this->render('admin/views/user_show.html.twig', [
            'user' => $this->getUser(),
        ]);
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
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/supprimer-utilisateur/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        // @phpstan-ignore-next-line
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
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
        // @phpstan-ignore-next-line
        if ($this->isCsrfTokenValid('delete' . $this->getUser()->getId(), $request->request->get('_token'))) {
            // @phpstan-ignore-next-line
            $entityManager->remove($this->getUser());
            $entityManager->flush();
        }

        $this->addFlash('primary', 'Ton compte a bien été supprimé, désolé·e·s de te voir partir :\'(');

        // @phpstan-ignore-next-line
        $this->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('showcase_home');
    }
}
