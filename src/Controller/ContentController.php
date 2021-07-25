<?php

namespace App\Controller;

use App\Entity\Catchphrase;
use App\Entity\History;
use App\Form\CatchphraseType;
use App\Form\HistoryType;
use App\Repository\CatchphraseRepository;
use App\Repository\HistoryRepository;
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
            return $this->redirectToRoute('admin_content-index');
        }

        return $this->render('admin/content/index.html.twig', [
            'catchphrase_form' => $catchphraseForm->createView(),
            'history_form' => $historyForm->createView(),
        ]);
    }
}
