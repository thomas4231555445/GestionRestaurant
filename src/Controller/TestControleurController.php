<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestControleurController extends AbstractController
{
    #[Route('/test/controleur', name: 'app_test_controleur')]
    public function index(): Response
    {
        return $this->render('test_controleur/ajout.html.twig', [
            'controller_name' => 'TestControleurController',
        ]);
    }
}
