<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_partner_")
 */
class PartnerController extends AbstractController
{
    /**
     * @Route("/ajouter-un-contact", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partner->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($partner);
            $entityManager->flush();
            $this->addFlash('success', 'Le contact a bien été ajouté!');

            return $this->redirectToRoute('admin_partners');
        }

        return $this->render('admin/partner/add.html.twig', [
            'partner' => $partner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-un-contact/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Partner $partner, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le contact a bien été modifié!');

            return $this->redirectToRoute('admin_partners');
        }

        return $this->render('admin/partner/edit.html.twig', [
            'partner' => $partner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-un-contact/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Partner $partner, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $partner->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($partner);
            $entityManager->flush();
            $this->addFlash('warning', 'Le contact a bien été supprimé!');
        }

        return $this->redirectToRoute('admin_partners');
    }

    /**
     * @Route("/ajax-partners/{category}/{query}", name="ajax")
     */
    public function getPartners(
        PartnerRepository $partnerRepository,
        string $category = '',
        string $query = ''
    ): Response {
        return $this->render('admin/partner/components/_partners_list.html.twig', [
            'partners' => $partnerRepository->findByQuery(
                urldecode($query),
                $category ?? null
            ),
        ]);
    }
}
