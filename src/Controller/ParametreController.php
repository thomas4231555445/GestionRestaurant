<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParametreController extends AbstractController
{

    #[Route('/indexparam', name: 'index_parametre')]
    public function index(): Response
    {
        return $this->render('parametres/index.html.twig');
    }

  #[Route('/parametre', name: 'parametre')]
    public function codeb(): Response
    {
        return $this->render('parametres/codebarres.html.twig');
    }
}
