<?php

namespace App\Controller;

use App\Entity\Catchphrase;
use App\Form\CatchphraseType;
use App\Repository\CatchphraseRepository;
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
        CatchphraseRepository $catchphraseRepository
    ): Response {
        $catchphrase = $catchphraseRepository->findOneBy([]) ?? new Catchphrase();
        $catchphraseForm = $this->createForm(CatchphraseType::class, $catchphrase);
        $catchphraseForm->handleRequest($request);

        if ($catchphraseForm->isSubmitted() && $catchphraseForm->isValid()) {
            if (!$catchphraseRepository->findOneBy([])) {
                $entityManager->persist($catchphrase);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Phrase d\'accroche modifiée');
            return $this->redirectToRoute('admin_content_index');
        }

        return $this->render('admin/content/index.html.twig', [
            'catchphrase_form' => $catchphraseForm->createView(),
        ]);
    }
}
