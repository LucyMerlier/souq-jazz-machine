<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_concert_")
 */
class ConcertController extends AbstractController
{
    /**
     * @Route("/proposer-une-date-de-concert", name="add", methods={"GET", "POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($concert);
            $entityManager->flush();
            $this->addFlash('success', 'Date de concert proposée, plus qu\'à attendre qque tout le monde ai voté!');
            return $this->redirectToRoute('admin_agenda');
        }

        return $this->render('admin/views/concert_add.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/modifier-le-concert/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Concert $concert
    ): Response {
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Concert modifié!');
            return $this->redirectToRoute('admin_agenda');
        }

        return $this->render('admin/views/concert_edit.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/valider-le-concert/{id}", name="validate", methods={"POST"})
     */
    public function validate(
        EntityManagerInterface $entityManager,
        Concert $concert
    ): Response {
        $concert->setIsValidated(true);
        $entityManager->flush();
        $this->addFlash('success', 'Date de concert validée!');
        return $this->redirectToRoute('admin_agenda');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/supprimer-le-concert/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Concert $concert): Response
    {
        // @phpstan-ignore-next-line
        if ($this->isCsrfTokenValid('delete' . $concert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($concert);
            $entityManager->flush();
            $this->addFlash('warning', 'Date de concert annulée!');
        }

        return $this->redirectToRoute('admin_agenda');
    }
}
