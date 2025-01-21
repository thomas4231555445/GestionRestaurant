<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Inventaire;
use App\Entity\Restaurant;
use App\Form\InventaireType;
use App\Form\VinsType;
use App\Repository\CommandeRepository;
use App\Repository\InventaireRepository;
use App\Repository\RestaurantRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class InventaireController extends AbstractController
{

    #[Route('/inventaire', name: 'listinventaire')]
    public function index(Security $security,Request $request,RestaurantRepository $restaurantRepository, InventaireRepository $inventaireRepository, VinsRepository $vinsRepository): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw new \Exception('User not connected');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);
        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $inventaires = $inventaireRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        $vins = $vinsRepository->findByRestaurantId($restaurant->getId());

        return $this->render('inventaire/index.html.twig', [
            'inventaires' => $inventaires,
            'vins' => $vins,
        ]);
    }

    #[Route('/inventaire/ajout', name: 'inventaire')]
    public function ajoutinventaire(Request $request, EntityManagerInterface $entityManager, Security $security, RestaurantRepository $restaurantRepository): Response
    {
        $inventaire = new Inventaire();


        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Vérifier si l'utilisateur est connecté et récupérer l'ID du restaurant
        if (!$user) {

            throw new \Exception('User not found');
        }

        // Récupérer le restaurant à partir de l'ID
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $idRestaurant = $restaurant->getId();

        $inventaireForm = $this->createForm(InventaireType::class, $inventaire, [
            'id_restaurant' => $idRestaurant,

        ]);



        $inventaireForm->handleRequest($request);

        if ($inventaireForm->isSubmitted() && $inventaireForm->isValid()) {
            // Récupérer l'ID du restaurant depuis le formulaire
            $idRestaurant = $inventaireForm->get('id_restaurant')->getData();

            // Définir l'ID du restaurant dans l'entité Inventaire
            $inventaire->setIdRestaurant($idRestaurant);



            $entityManager->persist($inventaire);
            $entityManager->flush();

            $this->addFlash('success', 'Vin Ajouté !');
            return $this->redirectToRoute('listinventaire');
        }

        return $this->render('inventaire/ajout.html.twig', [
            'inventaireForm' => $inventaireForm->createView(),
            'restaurant' => $restaurant,

        ]);
    }

    #[Route('/delete-ref/{id}', name: 'delete_ref')]
    public function deleteRef(int $id, InventaireRepository $inventaireRepository, EntityManagerInterface $entityManager): Response
    {
        $inventaire = $inventaireRepository->find($id);

        if (!$inventaire) {
            return new JsonResponse(['success' => false, 'message' => 'Commande introuvable.'], 404);
        }

        $entityManager->remove($inventaire);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Commande supprimée avec succès.']);
    }



}
