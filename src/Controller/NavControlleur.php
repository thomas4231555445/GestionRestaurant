<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NavControlleur extends AbstractController
{
    #[Route('/*', name: 'nav')]
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

}
