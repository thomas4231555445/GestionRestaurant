<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cave;
use App\Form\CaveType;
use App\Repository\CaveRepository;
use App\Repository\RestaurantRepository;
use App\Repository\VinsCaveRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CaveController extends AbstractController
{

    #[Route('/cave', name: 'vins_cave')]
    public function index(Security $security, RestaurantRepository $restaurantRepository, CaveRepository $caveRepository): Response
    {
        $user = $security->getUser();
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        $caves = $caveRepository->findByRestaurantId($restaurant->getId());

        return $this->render('cave/index.html.twig', [
            'caves' => $caves,
        ]);
    }

    #[Route ('/cave/ajout', name: 'cave_ajout')]
    public function ajout(EntityManagerInterface $entityManager, Security $security, Request $request, VinsRepository $vinsRepository, RestaurantRepository $restaurantRepository): Response
    {
        $cave = new Cave();

        $user = $security->getUser();
        if (!$user) {

            throw new \Exception('User not found');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $idRestaurant = $restaurant->getId();

        $caveForm = $this->createForm(CaveType::class, $cave, [
            'id_restaurant' => $idRestaurant,
        ]);

        $caveForm->handleRequest($request);

        if ($caveForm->isSubmitted() && $caveForm->isValid()) {
            $idRestaurant = $caveForm->get('id_restaurant')->getData();


            $cave->setIdRestaurant($idRestaurant);

            $entityManager->persist($cave);
            $entityManager->flush();

            return $this->redirectToRoute('vins_cave');
        }



        return $this->render('cave/ajout.html.twig', [
            'caveForm' => $caveForm->createView(),

        ]);
    }

    #[Route('/vins/caves/{id}', name: 'ma_cave')]
    public function maCave(int $id,VinsCaveRepository $vinsCaveRepository, Security $security, RestaurantRepository $restaurantRepository, CaveRepository $caveRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $security->getUser();

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        $selectedCave = $caveRepository->find($id);

        $vinsCave = $vinsCaveRepository->findBy(['id_cave' => $selectedCave->getId(), 'id_restaurant' => $user->getId(),]);



        $caves = $caveRepository->findByRestaurantId($restaurant->getId());


        $form = $this->createForm(CaveType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cave = $form->getData();
            $entityManager->persist($cave);
            $entityManager->flush();

            return new JsonResponse([
                'ligne' => $cave->getLigne(),
                'colonne' => $cave->getColonne()
                ]);
        }



        return $this->render('cave/macave.html.twig', [
            'caves' => $caves,
            'form' => $form->createView(),
            'selectedCave' => $selectedCave,
            'vinsCave' => $vinsCave,
        ]);
    }

    #[Route('/vins/caves/edit/{id}', name: 'edit_cave')]
    #[ParamConverter('cave', class: 'App\Entity\Cave')]
    public function editCave(Cave $cave, Request $request, EntityManagerInterface $entityManager, Security $security, VinsRepository $vinsRepository): Response
    {
        $form = $this->createForm(CaveType::class, $cave);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ma_cave');
        }

        return $this->render('cave/editcave.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
