<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Form\InstrumentType;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin", name="admin_instrument_")
 */
class InstrumentController extends AbstractController
{
    /**
     * @Route("/gestion-des-instruments", name="index", methods={"GET"})
     */
    public function index(InstrumentRepository $instrumentRepository): Response
    {
        return $this->render('admin/views/instrument/index.html.twig', [
            'instruments' => $instrumentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter-une-instrument", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $instrument = new Instrument();
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($instrument);
            $entityManager->flush();
            $this->addFlash('success', 'L\'instrument a bien été ajouté!');

            return $this->redirectToRoute('admin_instrument_index');
        }

        return $this->render('admin/views/instrument/add.html.twig', [
            'instrument' => $instrument,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-un-instrument/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Instrument $instrument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L\'instrument a bien été modifié!');

            return $this->redirectToRoute('admin_instrument_index');
        }

        return $this->render('admin/views/instrument/edit.html.twig', [
            'instrument' => $instrument,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-un-instrument/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Instrument $instrument, EntityManagerInterface $entityManager): Response
    {
        if (!$instrument->getPlayers()->isEmpty()) {
            $this->addFlash(
                'danger',
                'Impossible de supprimer cet instrument, ' .
                count($instrument->getPlayers()) .
                ' membre·s du groupe en joue·nt!!!'
            );
        } elseif ($this->isCsrfTokenValid('delete' . $instrument->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($instrument);
            $entityManager->flush();
            $this->addFlash('warning', 'L\'instrument a bien été supprimé!');
        }

        return $this->redirectToRoute('admin_instrument_index');
    }
}
