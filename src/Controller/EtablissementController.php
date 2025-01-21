<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EtablissementController extends AbstractController
{

    #[Route('/etablissement', name: 'etablissement_list')]
    public function index(RestaurantRepository $restaurantRepository): Response
    {

        $etablissements = $restaurantRepository->findAll();

        if (empty($etablissements)) {
            throw $this->createNotFoundException('Aucun établissement trouvé.');
        }

        return $this->render('etablissement/codebarres.html.twig', [
            "etablissements" => $etablissements,
        ]);
    }

    #[Route('/etablissement/{pseudo}', name: 'etablissement_show')]
    public function show(string $pseudo, UserRepository $userRepository, RestaurantRepository $restaurantRepository, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $connectedUser = $security->getUser();

        if (!$connectedUser) {
            throw new \Exception('User not connected');
        }

        // Récupérer l'utilisateur par pseudo
        $user = $userRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$user) {
            throw new \Exception('User not found');
        }

        // Récupérer le restaurant associé à l'utilisateur
        $etablissement = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$etablissement) {
            throw new \Exception('Restaurant not found');
        }

        $etablissementUser = $userRepository->findOneBy(['id' => $etablissement->getIdUsers()]);

        if (!$etablissementUser) {
            throw new \Exception('User associated with the restaurant not found');
        }

        // Vérifier si l'utilisateur connecté est le propriétaire du restaurant
        if ($connectedUser->getId() !== $etablissementUser->getId()) {
            throw new \Exception('Unauthorized access');
        }

        return $this->render('etablissement/show.html.twig', [
            "etablissement" => $etablissement,
            "user" => $user,
        ]);
    }


    #[Route('/create', name: 'app_create')]
    public function create(RestaurantRepository $restaurantRepository, Request $request, Security $security, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        $userId = $user->getId();

        $existingEtab = $restaurantRepository->findOneBy(['id_users' => $userId]);

        if ($existingEtab) {
            $etablissement = $existingEtab;

        } else {
            $etablissement = new Restaurant();
            $etablissement->setIdUsers($userId);
        }

        $etablissementForm = $this->createForm(RestaurantType::class, $etablissement);

        $etablissementForm->handleRequest($request);

        if ($etablissementForm->isSubmitted() && $etablissementForm->isValid()) {

            $etablissement->setIdUsers($userId);

            $entityManager->persist($etablissement);
            $entityManager->flush();

            $this->addFlash('success', 'Etablissement Ajouté !');
            return $this->redirectToRoute('dashboard', ['pseudo' => $user->getPseudo()]);
        }

        return $this->render('etablissement/create.html.twig', [
            'etablissementForm' => $etablissementForm->createView(),
        ]);
    }

    #[Route('/bddetablissement', name: 'bddetablissement_list')]
    public function bddetablissement(Security $security, UserRepository $userRepository, RestaurantRepository $restaurantRepository)
    {
        $connectedUser = $security->getUser();

        if (!$connectedUser) {
            throw new \Exception('User not connected');
        }

                $restaurants = $restaurantRepository->findAll();


            return $this->render('etablissement/bddetablissement.html.twig', [
                "restaurants" => $restaurants,
            ]);
        }
    }



