<?php

namespace App\Controller;

use App\Entity\Star;
use App\Form\StarType;
use App\Repository\BaseVinsRepository;
use App\Repository\StarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StarController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StarRepository $starRepository;

    public function __construct(EntityManagerInterface $entityManager, StarRepository $starRepository)
    {
        $this->entityManager = $entityManager;
        $this->starRepository = $starRepository;
    }

    #[Route('/star/{id}', name: 'star')]
    public function index(int $id, BaseVinsRepository $baseVinsRepository): Response
    {
        $basevins = $baseVinsRepository->findOneBy(['id' => $id]);
        $stars = $this->starRepository->findByBaseVins($id);

        // Calculer la moyenne des notes
        $totalRating = 0;
        $countRating = 0;
        foreach ($stars as $star) {
            $totalRating += $star->getStar();
            $countRating++;
        }
        $averageRating = $countRating > 0 ? $totalRating / $countRating : 0;

        return $this->render('star/stars.html.twig', [
            'basevins' => $basevins,
            'stars' => $stars,
            'averageRating' => $averageRating,
        ]);
    }

    #[Route('/star/stars/{id}', name: 'app_star')]
    public function star(int $id, BaseVinsRepository $baseVinsRepository, Request $request): Response
    {
        $star = new Star();
        $user = $this->getUser();

        $userId = $user->getId();
        $pseudo = $user->getPseudo();

        $baseVins = $baseVinsRepository->findOneBy(['id' => $id]);

        // Vérifier si une note existe déjà pour cet utilisateur et ce baseVins


        // Créer le formulaire
        $starForm = $this->createForm(StarType::class, $star, [
            'id_users' => $userId,
            'pseudo' => $pseudo,
        ]);

        // Gérer la soumission du formulaire
        $starForm->handleRequest($request);

        $existingStar = $this->starRepository->findByUserAndBaseVins($userId, $id);

        if ($existingStar) {
            // L'utilisateur a déjà mis une note pour ce baseVins
            return $this->render('star/already_rated.html.twig', [
                'baseVins' => $baseVins,
            ]);
        }

        if ($starForm->isSubmitted() && $starForm->isValid()) {
            // Récupérer les étoiles entrées dans le formulaire
            $starValue = $star->getStar();

            // Assigner les valeurs à l'objet Star
            $star->setIdUsers($userId);
            $star->setPseudo($pseudo);
            $star->setBaseVins($baseVins);
            $star->setStar($starValue);

            // Enregistrer l'objet Star dans la base de données
            $this->entityManager->persist($star);
            $this->entityManager->flush();

            // Rediriger ou afficher un message de succès
            return $this->redirectToRoute('main_home');
        }


        $stars = $this->starRepository->findByBaseVins($id);

        $totalRating = 0;
        $countRating = 0;
        foreach ($stars as $star) {
            $totalRating += $star->getStar();
            $countRating++;
        }
        $averageRating = $countRating > 0 ? $totalRating / $countRating : 0;

        // Rendre la vue avec le formulaire
        return $this->render('star/index.html.twig', [
            'starForm' => $starForm->createView(),
            'stars' => $stars,
            'averageRating' => $averageRating,
            'baseVins' => $baseVins,
        ]);
    }
}
