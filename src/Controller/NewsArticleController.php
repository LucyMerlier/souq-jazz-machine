<?php

namespace App\Controller;

use App\Entity\NewsArticle;
use App\Form\NewsArticleType;
use App\Repository\NewsArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin", name="admin_news_")
 */
class NewsArticleController extends AbstractController
{
    /**
     * @Route("/gestion-des-actus", name="index", methods={"GET"})
     */
    public function index(NewsArticleRepository $newsRepository): Response
    {
        return $this->render('admin/views/news/index.html.twig', [
            'news_articles' => $newsRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @Route("/ajouter-une-actu", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newsArticle = new NewsArticle();
        $form = $this->createForm(NewsArticleType::class, $newsArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsArticle->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($newsArticle);
            $entityManager->flush();
            $this->addFlash('success', 'L\'article a bien été ajouté!');

            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/views/news/add.html.twig', [
            'news_article' => $newsArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-une-actu/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NewsArticle $newsArticle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewsArticleType::class, $newsArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L\'article a bien été modifié!');

            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/views/news/edit.html.twig', [
            'news_article' => $newsArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-une-actu/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, NewsArticle $newsArticle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $newsArticle->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($newsArticle);
            $entityManager->flush();
            $this->addFlash('warning', 'L\'article a bien été supprimé!');
        }

        return $this->redirectToRoute('admin_news_index');
    }
}
