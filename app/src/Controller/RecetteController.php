<?php

namespace App\Controller;

use App\Entity\Recettes;
use App\Form\RecetteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RecetteController extends AbstractController
{
    #[Route('/recette/new', name: 'app_recette_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recettes();
        // Si l'utilisateur est connecté, on utilise setUserId au lieu de setUser
        if ($this->getUser()) {
            $recette->setUserId($this->getUser());
        }

        $form = $this->createForm(RecetteFormType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplace le fichier dans le dossier configuré (ex: public/uploads/images)
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // à définir dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', "Erreur lors de l'upload de l'image.");
                }

                $recette->setImage($newFilename);
            }

            $recette->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash('success', 'Votre recette a été créée avec succès!');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('recette/recette.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
