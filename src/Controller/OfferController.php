<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin", name="admin_offer_")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/gestion-des-petites-annonces", name="index", methods={"GET"})
     */
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('admin/offer/index.html.twig', [
            'offers' => $offerRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @Route("/ajouter-une-petite-annonce", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($offer);
            $entityManager->flush();
            $this->addFlash('success', 'La petite annonce a bien été ajoutée!');

            return $this->redirectToRoute('admin_offer_index');
        }

        return $this->render('admin/offer/add.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-une-petite-annonce/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La petite annonce a bien été modifiée!');

            return $this->redirectToRoute('admin_offer_index');
        }

        return $this->render('admin/offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-une-petite-annonce/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offer->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($offer);
            $entityManager->flush();
            $this->addFlash('warning', 'La petite annonce a bien été supprimée!');
        }

        return $this->redirectToRoute('admin_offer_index');
    }
}
