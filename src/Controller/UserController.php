<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InstrumentRepository;
use App\Form\UserType;
use App\Entity\User;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/tous-les-membres", name="index", methods={"GET"})
     */
    public function index(InstrumentRepository $instrumentRepository): Response
    {
        return $this->render('admin/views/user_index.html.twig', [
            'instruments' => $instrumentRepository->findAll(),
        ]);
    }

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
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/views/user_edit.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        // @phpstan-ignore-next-line
        if ($user->getId() === $this->getUser()->getId() || in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            // @phpstan-ignore-next-line
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $entityManager->remove($user);
                $entityManager->flush();
            }
        } else {
            $this->addFlash('danger', 'Tu n\'as pas les autorisations nécessaires pour supprimer ce compte');
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
