<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Fournisseurs;
use App\Form\FournisseursType;
use App\Repository\CommandeRepository;
use App\Repository\FournisseursRepository;
use App\Repository\RestaurantRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Dompdf\Dompdf;
use Dompdf\Options;

class CommandeController extends AbstractController
{

    #[Route('/commande', name: 'commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig');
    }

    #[Route('/mescommandes', name: 'mes_commandes')]
    public function mescommandes(CommandeRepository $commandeRepository, RestaurantRepository $restaurantRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found for the current user.');
        }

        $commandes = $commandeRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        if (empty($commandes)) {
            $this->addFlash('warning', 'No commands found for the current restaurant.');
        }

        return $this->render('commande/mescommandes.html.twig', [
            'commandes' => $commandes,
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/delete-commande/{id}', name: 'delete_commande')]
    public function deleteCommande(int $id, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            return new JsonResponse(['success' => false, 'message' => 'Commande introuvable.'], 404);
        }

        $entityManager->remove($commande);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Commande supprimée avec succès.']);
    }

    #[Route('/commande/create', name: 'create_command')]
    public function create(RestaurantRepository $restaurantRepository, FournisseursRepository $fournisseursRepository, VinsRepository $vinsRepository): Response
    {
        $user = $this->getUser();

        // Récupérer l'ID du restaurant de l'utilisateur connecté
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);
        $idRestaurant = $restaurant ? $restaurant->getId() : null;

        // Récupérer les fournisseurs associés à l'ID du restaurant
        $fournisseurs = $fournisseursRepository->findBy(['id_restaurant' => $idRestaurant]);

        // Récupérer les vins associés à l'ID du fournisseur sélectionné
        // Note : Cette partie sera gérée par AJAX dans le template Twig

        return $this->render('commande/create.html.twig', [
            'fournisseurs' => $fournisseurs,
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/vins/by-fournisseur/{id}', name: 'get_vins_by_fournisseur')]
    public function getVinsByFournisseur(int $id, VinsRepository $vinsRepository): JsonResponse
    {
        $vins = $vinsRepository->findBy(['id_fournisseur' => $id]);

        $vinsData = [];
        foreach ($vins as $vin) {
            $vinsData[] = [
                'id' => $vin->getId(),
                'nom' => $vin->getNomVin(),
                'appellation' => $vin->getAppellation(),
                'type' => $vin->getType(),
                'domaine' => $vin->getDomaine(),
                'cl' => $vin->getCl(),
                'nom_producteur' => $vin->getNomProducteur(),
                'millesime' => $vin->getMillesime(),
            ];
        }

        return new JsonResponse($vinsData);
    }

    #[Route('/fournisseur/create', name: 'create_fournisseur')]
    public function createfournisseur(Security $security, RestaurantRepository $restaurantRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        // Vérifier si l'utilisateur est connecté et récupérer l'ID du restaurant
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Récupérer le restaurant à partir de l'ID de l'utilisateur
        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }


        $fournisseurs = new Fournisseurs();

        $user = $this->getUser();

        $form = $this->createForm(FournisseursType::class, $fournisseurs, [
            'id_restaurant' => $restaurant->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($fournisseurs);
            $entityManager->flush();
            return $this->redirectToRoute('create_command');
        }
        return $this->render('commande/createfournisseur.html.twig', [
            'fournisseurs' => $fournisseurs,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(Request $request, FournisseursRepository $fournisseursRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $fournisseurId = $data['fournisseurId'];
        $vinsData = $data['vins'];

        $fournisseur = $fournisseursRepository->find($fournisseurId);

        // Générer le PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        $html = $this->renderView('commande/pdf.html.twig', [
            'fournisseur' => $fournisseur,
            'vins' => $vinsData,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="commande.pdf"',
        ]);
    }

    #[Route('/save-commande', name: 'save_commande')]
    public function saveCommande(EntityManagerInterface $entityManager, FournisseursRepository $fournisseursRepository, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // Récupérer les données envoyées
        $fournisseurId = $data['fournisseurId'];
        $restaurantId = $data['restaurantId']; // ID du restaurant de l'utilisateur connecté


        // Récupérer le fournisseur depuis la base de données
        $fournisseur = $fournisseursRepository->find($fournisseurId);

        if (!$fournisseur) {
            return new JsonResponse(['success' => false, 'message' => 'Fournisseur introuvable.'], 404);
        }

        // Créer une nouvelle commande
        $commande = new Commande();
        $commande->setIdFournisseur((int)$fournisseurId);
        $commande->setIdRestaurant((int)$restaurantId); // Assurez-vous que cette colonne existe dans votre table `commande`
        $commande->setNomFournisseur($fournisseur->getNom());
        $commande->setDateCommande(new \DateTime());

        // Sauvegarder la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Retourner une réponse JSON
        return new JsonResponse(['success' => true, 'message' => 'Commande enregistrée avec succès.']);
    }
}
