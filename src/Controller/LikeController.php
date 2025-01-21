<?php

namespace App\Controller;

use App\Entity\BaseVins;
use App\Entity\Like;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    public function like(int $id, UserRepository $userRepository, BaseVins $baseVins, EntityManagerInterface $entityManager, Request $request): Response
    {

    }

}
