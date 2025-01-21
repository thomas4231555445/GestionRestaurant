<?php

declare(strict_types=1);

// src/Controller/VinsCaveController.php

namespace App\Controller;

use App\Entity\Cave;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Entity\VinsCave;
use App\Form\VinsCaveType;
use App\Repository\CaveRepository;
use App\Repository\RestaurantRepository;
use App\Repository\VinsCaveRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class VinsCaveController extends AbstractController
{
    #[Route('/vins/cave/{id}', name: 'vinsCave')]
    public function index(int $id, VinsRepository $vinsRepository, VinsCaveRepository $vinsCaveRepository, Security $security, Request $request, EntityManagerInterface $entityManager, CaveRepository $caveRepository, RestaurantRepository $restaurantRepository): Response
    {
        $vinsCave = new VinsCave();
        $user = $security->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('User not found');
        }

        // Récupérer le restaurant associé à l'utilisateur
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant instanceof Restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }

        // Récupérer la cave associée au restaurant
        $cave = $caveRepository->findOneBy(['id_restaurant' => $restaurant->getId(), 'id' => $id]);

        if (!$cave instanceof Cave) {
            throw $this->createNotFoundException('Cave not found');
        }

        $vins = $vinsRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        // Vérifier si la cave contient des vins
        $vinsCaves = $vinsCaveRepository->findBy([
            'id_cave' => $cave->getId(),
            'id_restaurant' => $restaurant->getId(),
        ]);

        if (empty($vinsCaves) && $cave->getIdRestaurant() === $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }



        // Injecter l'ID de la cave dans le formulaire
        $vinsCave->setIdCave($cave->getId());
        $vinsCave->setIdRestaurant($restaurant->getId());

        $ligne = $request->query->get('ligne') ? (int) $request->query->get('ligne') : null;
        $colonne = $request->query->get('colonne') ? (int) $request->query->get('colonne') : null;

        $form = $this->createForm(VinsCaveType::class, $vinsCave, [
            'id_cave' => $cave->getId(),
            'ligne' => $ligne,
            'colonne' => $colonne,
            'id_restaurant' => $restaurant->getId(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ligne = (int)$form->get('ligne')->getData();
            $colonne = (int)$form->get('colonne')->getData();

            $existingVinsCave = $vinsCaveRepository->findOneBy([
                'id_cave' => $cave->getId(),
                'ligne' => $ligne,
                'colonne' => $colonne,
                'id_restaurant' => $restaurant->getId(),
            ]);

            if ($existingVinsCave) {
                $entityManager->remove($existingVinsCave);
                $entityManager->flush();
            }

            $vinsCave->setLigne($ligne);
            $vinsCave->setColonne($colonne);

            $entityManager->persist($vinsCave);
            $entityManager->flush();

            return $this->redirectToRoute('ma_cave', ['id' => $cave->getId()]);
        }

        $vinsCaves = $vinsCaveRepository->findBy([
            'id_cave' => $cave->getId(),
            'ligne' => $ligne,
            'colonne' => $colonne,
            'id_restaurant' => $restaurant->getId(),
        ]);

        $vinsInfo = [];
        foreach ($vinsCaves as $vinsCave) {
            $vin = $vinsRepository->findByCodeVin($vinsCave->getCodeVin());
            if ($vin) {
                $vinsInfo[] = $vin;
            }
        }

        return $this->render('cave/editcave.html.twig', [
            'vinsCaves' => $vinsCaves,
            'vinsInfo' => $vinsInfo,
            'form' => $form->createView(),
            'restaurant' => $restaurant,
            'vins' => $vins
        ]);
    }
}
