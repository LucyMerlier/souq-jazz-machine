<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Picture;
use App\Form\AlbumType;
use App\Form\PictureType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/admin", name="admin_album_")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/voir-les-photos/{id}", name="show", methods={"GET", "POST"})
     */
    public function show(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        Album $album
    ): Response {
        $form = $this->createForm(PictureType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('images')->getData();

            foreach ($pictures as $picture) {
                $image = new Picture();

                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('albums_directory'),
                        $newFilename
                    );
                } catch (FileException $exception) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement des photos :(');
                    return $this->redirectToRoute('admin_album_picture_add', ['id' => $album->getId()]);
                }

                $image->setImageUrl($newFilename);
                $image->setAlbum($album);

                $entityManager->persist($image);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Photo(s) ajoutée(s)!');
            return $this->redirectToRoute('admin_album_show', ['id' => $album->getId()]);
        }

        return $this->render('admin/album/show.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter-un-album", name="add", methods={"GET", "POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($album);
            $entityManager->flush();

            $this->addFlash('success', 'Album ajouté, plus qu\'à lui ajouter des images!');

            return $this->redirectToRoute('admin_album_picture_add', ['id' => $album->getId()]);
        }

        return $this->render('admin/album/add.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-un-album/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Album $album
    ): Response {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Album modifié!');
            return $this->redirectToRoute('admin_albums');
        }

        return $this->render('admin/album/edit.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-une-photo/{id}", name="picture_delete", methods={"POST"})
     */
    public function deleteImage(Request $request, EntityManagerInterface $entityManager, Picture $picture): Response
    {
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), (string)$request->request->get('_token'))) {
            $url = (string)$picture->getImageUrl();
            /** @var string */
            $path = $this->getParameter('albums_directory');
            unlink($path . $url);

            $entityManager->remove($picture);
            $entityManager->flush();
            $this->addFlash('warning', 'Photo supprimée!');
        }

        /** @var Album */
        $album = $picture->getAlbum();
        return $this->redirectToRoute('admin_album_show', ['id' => $album->getId()]);
    }

    /**
     * @Route("/supprimer-un-album/{id}", name="delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        Album $album
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $album->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($album);
            $entityManager->flush();

            foreach ($album->getPictures() as $picture) {
                $url = (string)$picture->getImageUrl();
                /** @var string */
                $path = $this->getParameter('albums_directory');
                unlink($path . $url);
            }

            $this->addFlash('warning', 'Album supprimé!');
        }

        return $this->redirectToRoute('admin_albums');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/modifier-la-visibilite/{id}", name="picture_toggle_visibility", methods={"POST"})
     */
    public function toggleVisibility(
        Request $request,
        EntityManagerInterface $entityManager,
        Picture $picture
    ): Response {
        if ($this->isCsrfTokenValid('visibility' . $picture->getId(), (string)$request->request->get('_token'))) {
            $picture->setIsVisible(!$picture->getIsVisible());
            $entityManager->flush();

            $this->addFlash('success', 'Visibilité modifiée!');
        }

        /** @var Album */
        $album = $picture->getAlbum();
        return $this->redirectToRoute('admin_album_show', [
            'id' => $album->getId(),
        ]);
    }
}
