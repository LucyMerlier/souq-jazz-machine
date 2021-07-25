<?php

namespace App\Controller;

use App\Entity\Catchphrase;
use App\Entity\History;
use App\Entity\LegalNotice;
use App\Form\CatchphraseType;
use App\Form\HistoryType;
use App\Form\LegalNoticeType;
use App\Repository\CatchphraseRepository;
use App\Repository\HistoryRepository;
use App\Repository\LegalNoticeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin", name="admin_content_")
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ContentController extends AbstractController
{
    /**
     * @Route("/gestion-du-contenu", name="index")
     */
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        CatchphraseRepository $catchphraseRepository,
        HistoryRepository $historyRepository
    ): Response {
        $catchphrase = $catchphraseRepository->findOneBy([]) ?? new Catchphrase();
        $catchphraseForm = $this->createForm(CatchphraseType::class, $catchphrase);
        $catchphraseForm->handleRequest($request);

        $history = $historyRepository->findOneBy([]) ?? new History();
        $historyForm = $this->createForm(HistoryType::class, $history);
        $historyForm->handleRequest($request);

        if ($catchphraseForm->isSubmitted() && $catchphraseForm->isValid()) {
            if (!$catchphraseRepository->findOneBy([])) {
                $entityManager->persist($catchphrase);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Phrase d\'accroche modifiée!');
            return $this->redirectToRoute('admin_content_index');
        }

        if ($historyForm->isSubmitted() && $historyForm->isValid()) {
            if (!$historyRepository->findOneBy([])) {
                $entityManager->persist($history);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Paragraphe "Notre histoire" modifié!');
            return $this->redirectToRoute('admin_content_index');
        }

        return $this->render('admin/content/index.html.twig', [
            'catchphrase_form' => $catchphraseForm->createView(),
            'history_form' => $historyForm->createView(),
        ]);
    }

    /**
     * @Route("/mentions-legales", name="legal_notices")
     */
    public function legalNotices(LegalNoticeRepository $legalNoticeRepository): Response
    {
        return $this->render('admin/content/legal_notices.html.twig', [
            'legal_notices' => $legalNoticeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter-une-mention-legale", name="legal_notice_add", methods={"GET","POST"})
     */
    public function addLegalNotice(Request $request, EntityManagerInterface $entityManager): Response
    {
        $legalNotice = new LegalNotice();
        $form = $this->createForm(LegalNoticeType::class, $legalNotice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($legalNotice);
            $entityManager->flush();
            $this->addFlash('success', 'La mention légale a bien été ajoutée!');

            return $this->redirectToRoute('admin_content_legal_notices');
        }

        return $this->render('admin/content/legal_notice_add.html.twig', [
            'legal_notice' => $legalNotice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-une-mention-legale/{id}", name="legal_notice_edit", methods={"GET","POST"})
     */
    public function editLegalNotice(
        Request $request,
        EntityManagerInterface $entityManager,
        LegalNotice $legalNotice
    ): Response {
        $form = $this->createForm(LegalNoticeType::class, $legalNotice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La mention légale a bien été modifiée!');

            return $this->redirectToRoute('admin_content_legal_notices');
        }

        return $this->render('admin/content/legal_notice_edit.html.twig', [
            'legal_notice' => $legalNotice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-une-mention-legale/{id}", name="legal_notice_delete", methods={"POST"})
     */
    public function deleteLegalNotice(
        Request $request,
        LegalNotice $legalNotice,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $legalNotice->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($legalNotice);
            $entityManager->flush();
            $this->addFlash('warning', 'La mention légale a bien été supprimée!');
        }

        return $this->redirectToRoute('admin_content_legal_notices');
    }
}
