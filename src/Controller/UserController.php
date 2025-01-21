<?php

namespace App\Controller;

use App\Entity\Memo;
use App\Entity\Post;
use App\Form\MemoType;
use App\Form\PostType;
use App\Repository\BaseVinsRepository;
use App\Repository\FournisseursRepository;
use App\Repository\InventaireRepository;
use App\Repository\MemoRepository;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use App\Repository\VinsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil')]
    public function index(int $id, UserRepository $userRepository, RestaurantRepository $restaurantRepository): Response
    {
        $user = $this->getUser();

        $users = $userRepository->findOneBy(['pseudo' => $user->getPseudo()]);


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
        if ($users->getId() !== $etablissementUser->getId()) {
            throw new \Exception('Unauthorized access');
        }

        return $this->render('user/index.html.twig', [
            "etablissement" => $etablissement,
            "users" => $user,
        ]);


    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(FournisseursRepository $fournisseursRepository,MemoRepository $memoRepository, Request $request, EntityManagerInterface $entityManager, InventaireRepository $inventaireRepository, BaseVinsRepository $baseVinsRepository, VinsRepository $vinsRepository, UserRepository $userRepository, RestaurantRepository $restaurantRepository): Response
    {
        $user = $this->getUser();

        $userId = $user->getId();

        // Récupérer l'id_restaurant correspondant à l'utilisateur connecté
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $userId]);
        $idRestaurant = $restaurant->getId();

        // Récupérer tous les vins avec cet id_restaurant
        $vins = $vinsRepository->findBy(['id_restaurant' => $idRestaurant]);

        $qtsVins = $inventaireRepository->createQueryBuilder('i')
            ->select('i.code_vin, SUM(i.qts) as total_qts')
            ->where('i.id_restaurant = :idRestaurant')
            ->andWhere('i.code_vin IN (:codeVins)')
            ->groupBy('i.code_vin')
            ->setParameter('idRestaurant', $idRestaurant)
            ->setParameter('codeVins', array_map(function($vin) { return $vin->getCodeVin(); }, $vins))
            ->getQuery()
            ->getResult();

        $qtsVinsArray = [];
        foreach ($qtsVins as $qtsVin) {
            $qtsVinsArray[$qtsVin['code_vin']] = $qtsVin['total_qts'];
        }

        // Faire la somme de toutes les lignes avec cet id_restaurant
        $totalVins = count($vins);

        $fournisseurs = $fournisseursRepository->findBy(['id_restaurant' => $idRestaurant]);

        $totalFournisseurs = count($fournisseurs);


        $basevins = $baseVinsRepository->findAllOrderedByIdDesc();

        $stockVins = 0;
        foreach ($vins as $vin) {
            $stockVins += $vin->getStock() * $vin->getPrixAchatHt();
        }





        $pseudo = $user->getPseudo();
        $avatar = $user->getAvatar();


        $memo = $memoRepository->findOneBy(['id_users' => $userId]);
        if (!$memo) {
            $memo = new Memo();
        }

        $memoForm = $this->createForm(MemoType::class, $memo);
        $memoForm->handleRequest($request);

        if ($memoForm->isSubmitted() && $memoForm->isValid()) {
            $memo = $memoForm->getData();
            $memo->setIdUsers($user->getId());

            $entityManager->persist($memo);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');

        }

            return $this->render('user/dashboard.html.twig', [
                'basevins' => $basevins,
                'vins' => $vins,
                'totalVins' => $totalVins,
                'stockVins' => $stockVins,
                'memoForm' => $memoForm->createView(),
                'memo' => $memo,
                'qtsVinsArray' => $qtsVinsArray,
                'totalFournisseurs' => $totalFournisseurs,
                ]);


        }

}
