<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Vins;
use App\Form\CarteType;
use App\Repository\CarteRepository;
use App\Repository\FournisseursRepository;
use App\Repository\RestaurantRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CarteController extends AbstractController
{
    #[Route('/indexcarte', name: 'indexcarte')]
    public function index(
        CarteRepository      $carteRepository,
        RestaurantRepository $restaurantRepository,
        Security             $security
    ): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw new AccessDeniedException('User not authenticated.');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found for the current user.');
        }

        $cartes = $carteRepository->findByRestaurantId($restaurant->getId());

        return $this->render('carte/index.html.twig', [
            'restaurant' => $restaurant,
            'cartes' => $cartes,
        ]);
    }

    #[Route('/selectedcarte/{id}', name: 'selectedcarte')]
    public function selectedCarte(
        int                  $id,
        CarteRepository      $carteRepository,
        RestaurantRepository $restaurantRepository,
        Security             $security
    ): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw new AccessDeniedException('User not authenticated.');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found for the current user.');
        }

        $selectedCartes = $carteRepository->find($id);

        $macartes = $carteRepository->findBy(['id' => $selectedCartes->getId(), 'id_restaurant' => $user->getId(),]);

        $cartes = $carteRepository->findByRestaurantId($restaurant->getId());;


        return $this->render('carte/selectedcarte.html.twig', [
            'restaurant' => $restaurant,
            'cartes' => $cartes,
            'selectedCartes' => $selectedCartes,
            'macartes' => $macartes,
        ]);
    }

    #[Route('/carte', name: 'carte')]
    public function carte(
        EntityManagerInterface $entityManager,
        Request                $request,
        Security               $security,
        RestaurantRepository   $restaurantRepository,
        CarteRepository        $carteRepository
    ): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw new AccessDeniedException('User not authenticated.');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found for the current user.');
        }

        $carte = new Carte();

        $vinsRepository = $entityManager->getRepository(Vins::class);
        $allReferences = $vinsRepository->findByRestaurantId($restaurant->getId());

        usort($allReferences, function ($a, $b) {
            // Assurez-vous que la méthode getCouleur() existe sur l'entité Vins
            return strcmp($a->getCouleur(), $b->getCouleur());
        });


        $referencesText = '<table>';
        $referencesText .= '<thead><tr><th>Couleur</th><th>Nom Producteur</th><th>Nom Vin</th><th>Millésime</th><th>Cl</th><th>Prix Vente TTC</th></tr></thead>';
        $referencesText .= '<tbody>';

        foreach ($allReferences as $vin) {
            $referencesText .= '<tr>';
            $referencesText .= sprintf(
                '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s Cl</td><td>%s €</td>',
                $vin->getCouleur(),
                $vin->getNomProducteur(),
                $vin->getNomVin(),
                $vin->getMillesime(),
                $vin->getCl(),
                $vin->getPrixVenteTTC()
            );
            $referencesText .= '</tr>';
        }

        $referencesText .= '</tbody></table>';

        $carteForm = $this->createForm(CarteType::class, $carte, [
            'id_restaurant' => $restaurant->getId(),
        ]);

        $carteForm->get('selection')->setData($referencesText);

        $carteForm->handleRequest($request);

        if ($carteForm->isSubmitted() && $carteForm->isValid()) {
            $references = $carteForm->get('selection')->getData();

            $backgroundColor = $carte->getBackground();

            if (empty(!$references)) {
                $references = ''; // Chaîne vide pour éviter les erreurs
            }

            $vinsSelection = [];
            // Si des références ont été saisies, on les traite
            if ($references) {
                preg_match_all('/<tr><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?) Cl<\/td><td>(.*?) €<\/td><\/tr>/', $references, $matches, PREG_SET_ORDER);
            }

            // Lier les vins sélectionnés à la carte
            foreach ($vinsSelection as $vinId) {
                $vin = $entityManager->getRepository(Vins::class)->find($vinId);
                if ($vin) {
                    $carte->addVin($vin); // Assurez-vous que l'entité Carte a une méthode `addVin`
                }
            }




        // Enregistrer la carte avec ses vins associés
        $carte->setIdRestaurant($restaurant->getId());
        $entityManager->persist($carte);
        $entityManager->flush();

        // Rediriger après la soumission réussie
        return $this->redirectToRoute('indexcarte');
    }

        $cartes = $carteRepository->findBy(['restaurant' => $restaurant]);

        return $this->render('carte/carte.html.twig', [
            'carteForm' => $carteForm->createView(),
            'restaurant' => $restaurant,
            'cartes' => $cartes,
        ]);
    }


}





