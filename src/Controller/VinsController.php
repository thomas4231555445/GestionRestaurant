<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BaseVins;
use App\Entity\Vins;
use App\Form\DescriptionType;
use App\Form\FournisseursType;
use App\Form\VinsModifType;
use App\Form\VinsStockType;
use App\Form\VinsType;
use App\Repository\BaseVinsRepository;
use App\Repository\CaveRepository;
use App\Repository\FournisseursRepository;
use App\Repository\InventaireRepository;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use App\Repository\VinsCaveRepository;
use App\Repository\VinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class VinsController extends AbstractController
{

    #[Route('/vins', name: 'vins_list')]
    public function list(InventaireRepository $inventaireRepository, BaseVinsRepository $baseVinsRepository, Security $security,RestaurantRepository $restaurantRepository,VinsRepository $vinsRepository): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw new \Exception('User not connected');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        $idRestaurant = $restaurant->getId();
        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $vins = $vinsRepository->findBy(['id_restaurant' => $restaurant->getIdUsers()]);



        return $this->render('vins/list.html.twig', [
            'vins' => $vins,

        ]);
    }

    #[Route('/vins/description/{id}', name: 'vins_description')]
    public function description(int $id,CaveRepository $caveRepository, VinsCaveRepository $vinsCaveRepository, InventaireRepository $inventaireRepository, BaseVinsRepository $baseVinsRepository, Security $security,RestaurantRepository $restaurantRepository,VinsRepository $vinsRepository): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw new \Exception('User not connected');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        $idRestaurant = $restaurant->getId();
        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $vin = $vinsRepository->findOneBy(['id' => $id]);

        if ($vin->getIdRestaurant() !== $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }

        $vins = $vinsRepository->findBy(['id' => $id]);



        $caves = $vinsCaveRepository->findBy(['id_restaurant' => $idRestaurant]);

        $vinsCave = $vinsCaveRepository->createQueryBuilder('vc')
            ->select('vc.code_vin, vc.id_cave, vc.ligne, vc.colonne')
            ->where('vc.id_restaurant = :idRestaurant')
            ->andWhere('vc.code_vin = :codeVin')
            ->setParameter('idRestaurant', $idRestaurant)
            ->setParameter('codeVin', $vins[0]->getCodeVin())
            ->getQuery()
            ->getResult();

        $caves = $caveRepository->createQueryBuilder('c')
            ->select('c.id, c.num_cave')
            ->where('c.id_restaurant = :idRestaurant')
            ->setParameter('idRestaurant', $idRestaurant)
            ->getQuery()
            ->getResult();

        $cavesArray = [];
        foreach ($caves as $cave) {
            $cavesArray[$cave['id']] = $cave['num_cave'];
        }



        return $this->render('vins/description.html.twig', [
            'vins' => $vins,
            'vinsCave' => $vinsCave,
            'cavesArray' => $cavesArray,

        ]);
    }

    #[Route('/ajoutvins', name: 'ajout_vins')]
    public function ajoutvins(Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
        $vin = new Vins();
        $baseVin = new BaseVins();

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

        $baseVins = $baseVinsRepository->findAll();

        $vinsForm = $this->createForm(VinsType::class, $vin, [
            'id_restaurant' => $idRestaurant,
        ]);
        $vinsForm->handleRequest($request);

        if ($vinsForm->isSubmitted() && $vinsForm->isValid()) {
            $codeVin = $vinsForm->get('code_vin')->getData();

            // Vérifier si le code_vin existe déjà pour ce restaurant
            $existingVin = $vinsRepository->createQueryBuilder('v')
                ->andWhere('v.code_vin = :codeVin')
                ->andWhere('v.restaurant = :idRestaurant')
                ->setParameter('codeVin', $codeVin)
                ->setParameter('idRestaurant', $idRestaurant)
                ->getQuery()
                ->getOneOrNullResult();


            if ($existingVin) {
                $this->addFlash('error', 'Le code vin existe déjà, merci de le modifier.');
                return $this->redirectToRoute('ajout_vins');
            }

            $restaurant = $restaurantRepository->find($vinsForm->get('id_restaurant')->getData());

            if (!$restaurant) {
                throw new \Exception('Restaurant not found');
            }

            $vin->setRestaurant($restaurant);

            $baseVin->setCouleur($vin->getCouleur());
            $baseVin->setType($vin->getType());
            $baseVin->setAppellation($vin->getAppellation());
            $baseVin->setNomProducteur($vin->getNomProducteur());
            $baseVin->setDomaine($vin->getDomaine());
            $baseVin->setNomVin($vin->getNomVin());
            $baseVin->setCl($vin->getCl());
            $baseVin->setMillesime($vin->getMillesime());
            $baseVin->setDescription($vinsForm->get('description')->getData());
            $baseVin->setContact($vinsForm->get('contact')->getData());
            $baseVin->setActus($vinsForm->get('actus')->getData());

            $entityManager->persist($vin);
            $entityManager->persist($baseVin);
            $entityManager->flush();

            $this->addFlash('success', 'Vin Ajouté !');
            return $this->redirectToRoute('vins_list');
        }

    return $this->render('vins/ajout.html.twig', [
        'vinsForm' => $vinsForm->createView(),
        'basevins' => $baseVins,

    ]);


    }

    #[Route('/ajoutvinsrecu/{id}', name: 'ajout_vins_recu')]
    public function ajoutvinsrecu(int $id, Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
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

        if ($user->getId() !== $restaurant->getIdUsers()) {
            throw new \Exception('Vous n\'avez pas la permission');
        }


        $idRestaurant = $restaurant->getId();

        $existingVin = $vinsRepository->findOneBy(['id' => $id]);

        $vin = new Vins();
        $vinsForme = $this->createForm(VinsStockType::class, $vin, [
            'id_restaurant' => $idRestaurant,
        ]);
        $vinsForme->handleRequest($request);

        if ($vinsForme->isSubmitted() && $vinsForme->isValid()) {
            $stockToAdd = $vinsForme->get('stock')->getData();

            // Ajouter la valeur du stock
            $existingVin->setStock((int) $existingVin->getStock() + $stockToAdd);

            $entityManager->persist($existingVin);
            $entityManager->flush();

            $this->addFlash('success', 'Stock du vin mis à jour !');
            return $this->redirectToRoute('vins_description',  ['id' => $existingVin->getId()]);
        }

        return $this->render('vins/reception.html.twig', [
            'vinsForme' => $vinsForme->createView(),

        ]);


    }

    #[Route('/enlevevinsrecu/{id}', name: 'enleve_vins_recu')]
    public function enlevevinsrecu(int $id, Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
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

        $existingVin = $vinsRepository->findOneBy(['id' => $id]);

        if ($existingVin->getIdRestaurant() !== $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }

        $vin = new Vins();
        $vinsForme = $this->createForm(VinsStockType::class, $vin, [
            'id_restaurant' => $idRestaurant,
        ]);
        $vinsForme->handleRequest($request);

        if ($vinsForme->isSubmitted() && $vinsForme->isValid()) {
            $stockToAdd = $vinsForme->get('stock')->getData();

            // Ajouter la valeur du stock
            $existingVin->setStock((int) $existingVin->getStock() - $stockToAdd);

            $entityManager->persist($existingVin);
            $entityManager->flush();

            $this->addFlash('success', 'Stock du vin mis à jour !');
            return $this->redirectToRoute('vins_description',  ['id' => $existingVin->getId()]);
        }

        return $this->render('vins/enleve.html.twig', [
            'vinsForme' => $vinsForme->createView(),

        ]);


    }

    #[Route('/descriptionref/{id}', name: 'descriptionref')]
    public function descriptionref(int $id, Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
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

        $vins = $vinsRepository->findBy(['id' => $id]);

        $vin = $vinsRepository->findOneBy(['id' => $id]);

        if ($vin->getIdRestaurant() !== $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }

        $baseVins = $baseVinsRepository->findAll();

        // Comparer les données et trouver la description correspondante
        $description = null;
        foreach ($baseVins as $baseVin) {
            if ($baseVin->getCouleur() === $vin->getCouleur() &&
                $baseVin->getType() === $vin->getType() &&
                $baseVin->getAppellation() === $vin->getAppellation() &&
                $baseVin->getNomProducteur() === $vin->getNomProducteur() &&
                $baseVin->getDomaine() === $vin->getDomaine() &&
                $baseVin->getNomVin() === $vin->getNomVin() &&
                $baseVin->getMillesime() === $vin->getMillesime()) {
                $description = $baseVin->getDescription();
                break;
            }
        }

        $form = $this->createForm(DescriptionType::class, $vin, [
            'data' => ['description' => $description], // Pré-remplir le champ description
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $descToAdd = $form->get('description')->getData();

            // Mettre à jour la description dans la table base_vins
            $existingBaseVin = $baseVinsRepository->findOneBy([
                'couleur' => $vin->getCouleur(),
                'type' => $vin->getType(),
                'appellation' => $vin->getAppellation(),
                'nom_producteur' => $vin->getNomProducteur(),
                'domaine' => $vin->getDomaine(),
                'nom_vin' => $vin->getNomVin(),
                'millesime' => $vin->getMillesime(),
            ]);

            if ($existingBaseVin) {
                $existingBaseVin->setDescription($descToAdd);
                $entityManager->persist($existingBaseVin);
                $entityManager->flush();

                $this->addFlash('success', 'Description mise à jour !');
            }

            return $this->redirectToRoute('descriptionref', ['id' => $vin->getId()]);
        }

        return $this->render('vins/descriptionref.html.twig', [
            'vin' => $vin,
            'vins' => $vins,
            'baseVins' => $baseVins,
            'description' => $description,
            'form' => $form->createView(),
        ]);


    }

    #[Route('/descriptionrefother/{id}', name: 'descriptionrefother')]
    public function descriptionrefother(int $id, Security $security, BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository, Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
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

        // Récupérer le vin à partir de l'ID
        $vins = $vinsRepository->findOneBy(['id' => $id]);

        if (!$vins) {
            throw new \Exception('Vin not found');
        }

        if ($vins->getIdRestaurant() !== $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }

        $baseVins = $baseVinsRepository->findAll();

        // Comparer les données et trouver la description correspondante
        $baseVinMatch = null;
        foreach ($baseVins as $baseVin) {
            if ($baseVin->getCouleur() === $vins->getCouleur() &&
                $baseVin->getType() === $vins->getType() &&
                $baseVin->getAppellation() === $vins->getAppellation() &&
                $baseVin->getNomProducteur() === $vins->getNomProducteur() &&
                $baseVin->getDomaine() === $vins->getDomaine() &&
                $baseVin->getNomVin() === $vins->getNomVin() &&
                $baseVin->getMillesime() === $vins->getMillesime()) {
                $baseVinMatch = $baseVin;
                break;
            }
        }

        // Pré-remplir les champs du formulaire
        if ($baseVinMatch) {
            $vins->setCouleur($baseVinMatch->getCouleur());
            $vins->setType($baseVinMatch->getType());
            $vins->setAppellation($baseVinMatch->getAppellation());
            $vins->setNomProducteur($baseVinMatch->getNomProducteur());
            $vins->setDomaine($baseVinMatch->getDomaine());
            $vins->setNomVin($baseVinMatch->getNomVin());
            $vins->setMillesime($baseVinMatch->getMillesime());
        }

        $form = $this->createForm(VinsModifType::class, $vins, [
            'id_restaurant' => $restaurant->getId(),

        ]);

        if ($baseVinMatch) {
            $form->get('description')->setData($baseVinMatch->getDescription());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vins->setCouleur($form->get('couleur')->getData());
            $vins->setType($form->get('type')->getData());
            $vins->setAppellation($form->get('appellation')->getData());
            $vins->setNomProducteur($form->get('nom_producteur')->getData());
            $vins->setDomaine($form->get('domaine')->getData());
            $vins->setNomVin($form->get('nom_vin')->getData());
            $vins->setCl($form->get('cl')->getData());
            $vins->setMillesime($form->get('millesime')->getData());

            $entityManager->persist($vins);
            $entityManager->flush();

            if ($baseVinMatch) {
                $baseVinMatch->setCouleur($vins->getCouleur());
                $baseVinMatch->setType($vins->getType());
                $baseVinMatch->setAppellation($vins->getAppellation());
                $baseVinMatch->setNomProducteur($vins->getNomProducteur());
                $baseVinMatch->setDomaine($vins->getDomaine());
                $baseVinMatch->setNomVin($vins->getNomVin());
                $baseVinMatch->setMillesime($vins->getMillesime());
                $baseVinMatch->setDescription($form->get('description')->getData());
                $entityManager->persist($baseVinMatch);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Description mise à jour !');

            return $this->redirectToRoute('descriptionref', ['id' => $vins->getId()]);
        }


        return $this->render('vins/descriptionrefother.html.twig', [
            'vins' => $vins,
            'baseVins' => $baseVins,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/fournisseurs', name: 'mes_fournisseur')]
    public function listfournisseurs(FournisseursRepository $fournisseursRepository, Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw new \Exception('User not connected');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $fournisseurs = $fournisseursRepository->findBy(['id_restaurant' => $restaurant->getId()]);



        return $this->render('fournisseurs/list.html.twig', [
            'fournisseurs' => $fournisseurs,

        ]);
    }

    #[Route('/descriptionfour/{id}', name: 'descriptionfour')]
    public function descriptionfour(int $id, Security $security, RestaurantRepository $restaurantRepository, FournisseursRepository $fournisseursRepository): Response
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
        $fournisseurs = $fournisseursRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        // Récupérer le fournisseur à partir de l'ID
        $fournisseur = $fournisseursRepository->findOneBy(['id' => $id]);

        if (!$fournisseur) {
            throw new \Exception('Vin not found');
        }

        if ($fournisseur->getIdRestaurant() !== $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }

        return $this->render('fournisseurs/descriptionfour.html.twig', [
            'fournisseur' => $fournisseur,
            'restaurant' => $restaurant,
            'fournisseurs' => $fournisseurs,
        ]);

    }

    #[Route('/descriptionfourother/{id}', name: 'descriptionfourother')]
    public function descriptionfourother(int $id,FournisseursRepository $fournisseursRepository, Security $security, BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository, Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
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

        // Récupérer le vin à partir de l'ID
        $fournisseur = $fournisseursRepository->findOneBy(['id' => $id]);

        $fournisseurs = $fournisseursRepository->findBy(['id_restaurant' => $restaurant->getId()]);

        if (!$fournisseur) {
            throw new \Exception('Vin not found');
        }

        if ($fournisseur->getIdRestaurant() !== $restaurant->getId()) {
            throw new \Exception('Vous n\'avez pas la permission de voir ce vin.');
        }


        // Pré-remplir les champs du formulaire
        if ($fournisseur) {
            $fournisseur->setNom($fournisseur->getNom());
            $fournisseur->setSiren($fournisseur->getSiren());
            $fournisseur->setPrenom($fournisseur->getPrenom());
            $fournisseur->setNomFamille($fournisseur->getNomFamille());
            $fournisseur->setPays($fournisseur->getPays());
            $fournisseur->setAdresse($fournisseur->getAdresse());
            $fournisseur->setCodePostale($fournisseur->getCodePostale());
            $fournisseur->setVille($fournisseur->getVille());
            $fournisseur->setTelephoneperso($fournisseur->getTelephoneperso());
            $fournisseur->setMailperso($fournisseur->getMailperso());
            $fournisseur->setTelephone($fournisseur->getTelephone());
            $fournisseur->setMail($fournisseur->getMail());
            $entityManager->persist($fournisseur);
            $entityManager->flush();
        }

        $form = $this->createForm(FournisseursType::class, $fournisseur, [
            'id_restaurant' => $restaurant->getId(),

        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseur->setNom($form->get('nom')->getData());
            $fournisseur->setSiren($form->get('siren')->getData());
            $fournisseur->setPrenom($form->get('prenom')->getData());
            $fournisseur->setNomFamille($form->get('nom_famille')->getData());
            $fournisseur->setPays($form->get('pays')->getData());
            $fournisseur->setAdresse($form->get('adresse')->getData());
            $fournisseur->setCodePostale($form->get('code_postale')->getData());
            $fournisseur->setVille($form->get('ville')->getData());
            $fournisseur->setTelephoneperso($form->get('telephoneperso')->getData());
            $fournisseur->setMailperso($form->get('mailperso')->getData());
            $fournisseur->setTelephone($form->get('telephone')->getData());
            $fournisseur->setMail($form->get('mail')->getData());

            $entityManager->persist($fournisseur);
            $entityManager->flush();


            $this->addFlash('success', 'Description mise à jour !');

            return $this->redirectToRoute('descriptionfour', ['id' => $fournisseur->getId()]);
        }


        return $this->render('fournisseurs/descriptionfourother.html.twig', [
            'fournisseur' => $fournisseur,
            'fournisseurs' => $fournisseurs,
            'form' => $form->createView(),

        ]);
    }


    #[Route('/basevins', name: 'base_vins')]
    public function listbasevins(Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw new \Exception('User not connected');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $baseVins = $baseVinsRepository->findAll();



        return $this->render('basevins/list.html.twig', [
            'basevins' => $baseVins,

        ]);
    }

    #[Route('/basevins/descritpion/{id}', name: 'base_vins_description')]
    public function descriptionbasevins(int $id, Security $security,BaseVinsRepository $baseVinsRepository, RestaurantRepository $restaurantRepository,  Request $request, VinsRepository $vinsRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw new \Exception('User not connected');
        }

        $restaurant = $restaurantRepository->findOneBy(['id_users' => $user->getId()]);

        if (!$restaurant) {
            throw new \Exception('Restaurant not found');
        }

        $baseVins = $baseVinsRepository->find($id);



        return $this->render('basevins/description.html.twig', [
            'basevins' => $baseVins,

        ]);
    }

    #[Route('/details_viti', name: 'details_viti')]
    public function detailsviti(Request $request, RestaurantRepository $restaurantRepository, Security $security, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $contact = $request->get('contact');
        $restaurant = $restaurantRepository->findOneBy(['nomRestaurant' => $contact]);

        if ($restaurant) {
            return JsonResponse([
                'success' =>true,
                'nom' => $restaurant->getNomRestaurant(),
                'telephone' => $restaurant->getTelephone(),
            ]);
        }
        return new JsonResponse(['success' => false]);
    }


}
