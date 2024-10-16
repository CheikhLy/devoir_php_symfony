<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ClientRepository;
use App\Form\ClientType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
class ClientController extends AbstractController
{
    /**
     * @Route("/clients", name="clients.index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository,Request $request): Response
    {
        $page = (int) $request->query->get('page', 1); // Récupérer la page actuelle, par défaut 1
        $limit = 10; // Nombre d'éléments par page
    
        // Récupérer tous les clients
        $clients = $clientRepository->findAll();
        $totalClients = count($clients);
        $totalPages = ceil($totalClients / $limit);
    
        // Récupérer les clients pour la page actuelle
        $offset = ($page - 1) * $limit;
        $clients = array_slice($clients, $offset, $limit);
    
        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    
    }
    /**
     * @Route("/clients/store", name="clients.store", methods={"GET", "POST"})
     */
    public function store(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $client = new Client(); // Création d'un nouvel objet Client
        
        
         $form = $this->createForm(ClientType::class, $client); // Création du formulaire pour le client

        $form->handleRequest($request); // Lancement de la requête HTTP et analyse des données envoyées par le client

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setCreateAt(new \DateTimeImmutable()); // Fixer la date de création
            $client->setUpdateAt(new \DateTimeImmutable()); // Fixer la date de mise à jour
            
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('clients.index');
        }

        // Gérer les erreurs ici si le formulaire n'est pas valide
        return $this->render('client/form.html.twig', [
            'formClient' => $form->createView(), // Correction ici
        ]);
    }
    
        //utilisation des path variables

        /**
     * @Route("/clients/show/{id?}", name="clients.show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        dd($id);
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
    //utilisation des query parameters
    #[Route('/clients/search', name: 'clients.search')]
    
   
    public function searchClientByTelephone(Request $request, ClientRepository $clientRepository): Response
        {
            $telephone = $request->query->get('tel');
            
            // Rechercher les clients par téléphone
            $clients = $clientRepository->findBy(['telephon' => $telephone]);
    
            return $this->render('client/index.html.twig', [
                'datas' => $clients, // Passer les clients trouvés à la vue
            ]);
        }

       /**
     * @Route("/clients/remove/{id?}", name="clients.remove", methods={"GET"})
     */
    public function remouve(int $id): Response
    { 

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

}
