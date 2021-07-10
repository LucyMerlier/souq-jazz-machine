<?php

namespace App\Controller;

use App\Entity\MusicSheet;
use App\Entity\Song;
use App\Entity\User;
use App\Form\MusicSheetType;
use App\Form\SongType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_song_")
 */
class SongController extends AbstractController
{
    /**
     * @Route("/ajouter-un-morceau", name="add", methods={"GET", "POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $user = $this->getUser();
            $song->setOwner($user);
            $song->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($song);
            $entityManager->flush();

            $this->addFlash('success', 'Morceau ajouté, plus qu\'à lui associer des partitions!');

            return $this->redirectToRoute('admin_song_sheet_add', ['id' => $song->getId()]);
        }

        return $this->render('admin/song/add.html.twig', [
            'song' => $song,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-le-morceau/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Song $song
    ): Response {
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Morceau modifié!');
            return $this->redirectToRoute('admin_songs');
        }

        return $this->render('admin/song/edit.html.twig', [
            'song' => $song,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter-une-partition/{id}", name="sheet_add", methods={"GET", "POST"})
     */
    public function addSheet(
        Request $request,
        EntityManagerInterface $entityManager,
        Song $song
    ): Response {
        $sheet = new MusicSheet();
        $sheet->setSong($song);
        $form = $this->createForm(MusicSheetType::class, $sheet, [
            'validation_groups' => ['admin_song_sheet_add'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sheet->setUpdatedAt(new DateTime('now'));
            $entityManager->persist($sheet);
            $entityManager->flush();
            $this->addFlash('success', 'Partition ajoutée! En ajouter une autre?');
            return $this->redirectToRoute('admin_song_sheet_add', ['id' => $song->getId()]);
        }

        return $this->render('admin/song/sheet_add.html.twig', [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-la-partition/{id}", name="sheet_edit", methods={"GET", "POST"})
     */
    public function editSheet(
        Request $request,
        EntityManagerInterface $entityManager,
        MusicSheet $sheet
    ): Response {
        $form = $this->createForm(MusicSheetType::class, $sheet, [
            'validation_groups' => ['admin_song_sheet_edit'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sheet->setUpdatedAt(new DateTime('now'));
            $entityManager->flush();
            $this->addFlash('success', 'Partition modifiée!');

            /** @var song */
            $song = $sheet->getSong();
            return $this->redirectToRoute('admin_song_edit', ['id' => $song->getId()]);
        }

        return $this->render('admin/song/sheet_edit.html.twig', [
            'sheet' => $sheet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-la-partition/{id}", name="sheet_delete", methods={"POST"})
     */
    public function deleteSheet(Request $request, EntityManagerInterface $entityManager, MusicSheet $sheet): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sheet->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($sheet);
            $entityManager->flush();
            $this->addFlash('warning', 'Partition supprimée!');
        }

        /** @var Song */
        $song = $sheet->getSong();
        return $this->redirectToRoute('admin_song_edit', ['id' => $song->getId()]);
    }

    /**
     * @Route("/supprimer-le-morceau/{id}", name="delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        Song $song
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $song->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($song);
            $entityManager->flush();

            $this->addFlash('warning', 'Morceau supprimé!');
        }

        return $this->redirectToRoute('admin_songs');
    }
}
