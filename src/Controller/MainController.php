<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Fournisseurs;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\BaseVinsRepository;
use App\Repository\FournisseursRepository;
use App\Repository\NotesRepository;
use App\Repository\PostRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StarRepository;
use App\Repository\VinsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{


    #[Route('/', name: 'main')]
    public function index(BaseVinsRepository $baseVinsRepository): Response
    {
        return $this->render('main/index.html.twig', []);
    }




    #[Route('/actu', name: 'main_home')]
    public function filactu(FournisseursRepository $fournisseursRepository, EntityManagerInterface $entityManager, Request $request, PostRepository $postRepository, StarRepository $starRepository, RestaurantRepository $restaurantRepository, VinsRepository $vinsRepository, NotesRepository $notesRepository, Security $security, BaseVinsRepository $baseVinsRepository): Response
    {
        $user = $this->getUser();

        $userId = $user->getId();

        // Récupérer l'id_restaurant correspondant à l'utilisateur connecté
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $userId]);

        $idRestaurant = $restaurant->getId();

        // Récupérer tous les vins avec cet id_restaurant
        $vins = $vinsRepository->findBy(['id_restaurant' => $idRestaurant]);

        // Faire la somme de toutes les lignes avec cet id_restaurant
        $totalVins = count($vins);



        $star = $starRepository->findBy(['id_users' => $userId]);

        $totalStar = count($star);



        $basevins = $baseVinsRepository->findAllOrderedByIdDesc();

        foreach ($basevins as $basevin) {
            $notes = $notesRepository->findBy(['id_vin' => $basevin->getId()]);
            $basevin->setNotesCollection(new ArrayCollection($notes));
        }

        foreach ($vins as $vin) {

        }

        $pseudo = $user->getPseudo();
        $avatar = $user->getAvatar();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $post->setIdUsers($userId);
            $post->setPseudo($pseudo);
            $post->setAvatar($avatar);


            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }


        $posts = $postRepository->findBy(['id_users' => $userId], ['id' => 'DESC']);

        return $this->render('main/home.html.twig', [
            'basevins' => $basevins,
            'vins' => $vins,
            'totalVins' => $totalVins,
            'star' => $star,
            'totalStar' => $totalStar,
            'posts' => $posts,
            'form' => $form->createView(),

        ]);

    }

    #[Route('/actus', name: 'actus_membres')]
    public function membresactus(EntityManagerInterface $entityManager, Request $request, PostRepository $postRepository, StarRepository $starRepository, RestaurantRepository $restaurantRepository, VinsRepository $vinsRepository, NotesRepository $notesRepository, Security $security, BaseVinsRepository $baseVinsRepository): Response
    {
        $user = $this->getUser();

        $userId = $user->getId();

        // Récupérer l'id_restaurant correspondant à l'utilisateur connecté
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $userId]);

        $idRestaurant = $restaurant->getId();

        // Récupérer tous les vins avec cet id_restaurant
        $vins = $vinsRepository->findBy(['id_restaurant' => $idRestaurant]);

        // Faire la somme de toutes les lignes avec cet id_restaurant
        $totalVins = count($vins);

        $star = $starRepository->findBy(['id_users' => $userId]);

        $totalStar = count($star);



        $basevins = $baseVinsRepository->findAllOrderedByIdDesc();

        foreach ($basevins as $basevin) {
            $notes = $notesRepository->findBy(['id_vin' => $basevin->getId()]);
            $basevin->setNotesCollection(new ArrayCollection($notes));
        }

        foreach ($vins as $vin) {

        }

        $pseudo = $user->getPseudo();
        $avatar = $user->getAvatar();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $post->setIdUsers($userId);
            $post->setPseudo($pseudo);
            $post->setAvatar($avatar);
            $post->setUser($user);


            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('actus_membres');
        }


        $posts = $entityManager->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('main/actusmembres.html.twig', [
            'basevins' => $basevins,
            'vins' => $vins,
            'totalVins' => $totalVins,
            'star' => $star,
            'totalStar' => $totalStar,
            'posts' => $posts,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/submit-comment', name: 'submit_comment', methods: ['POST'])]
    public function submitComment(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();

        $pseudo = $user->getPseudo();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $post->setIdUsers($userId);
            $post->setPseudo($pseudo);


            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
