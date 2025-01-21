<?php

namespace App\Controller;


use App\Entity\Notes;
use App\Entity\Vins;
use App\Form\NotesType;
use App\Repository\BaseVinsRepository;
use App\Repository\NotesRepository;
use App\Repository\UserRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NotesController extends AbstractController
{
    #[Route('/notes', name: 'app_notes')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/notes/create/{id}', name: 'app_notes_create')]
    public function create(NotesRepository $notesRepository, VinsRepository $vinsRepository, BaseVinsRepository $baseVinsRepository, int $id, Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        $connectedUser = $security->getUser();

        $vin = $vinsRepository->find($id);

        $note = new Notes();
        $note->setIdUsers($connectedUser->getId());
        $note->setIdVin($vin->getId());
        $note->setUser($connectedUser);

        $form = $this->createForm(NotesType::class, $note);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingNote = $notesRepository->findOneBy(['id_vin' => $vin->getId()]);

            if($existingNote){
                $entityManager->remove($existingNote);
                $entityManager->flush();
            }


            $entityManager->persist($note);
            $entityManager->flush();


            $this->addFlash('success', 'Note Ajouté !');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('notes/create.html.twig', [
            'form' => $form->createView(),

        ]);
    }


}
