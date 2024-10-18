<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SearchClientType;
//  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//  use App\Form\SearchClientType;

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
     * @Route("/clients", name="clients.index", methods={"GET","POST"})
     */
    public function index(ClientRepository $clientRepository,Request $request): Response
    {

        // $formSearch = $this->createForm(SearchClientType::class);
        // $formSearch->handleRequest($request);
        // $formSearch->addEventSubscriber(new SearchClientSubscriber());
        // if ($formSearch->isSubmitted($request) && $formSearch->isValid()) {
         
        //     $clients = $clientRepository->findBy(['telephon' => $formSearch->get('telephon')->getData()]);
         
        // }else {
        //     // Récupérer tous les clients
        //     $clients = $clientRepository->findAll();
        // }
        // $page = (int) $request->query->get('page', 1); // Récupérer la page actuelle, par défaut 1
        // $limit = 4; // Nombre d'éléments par page
    
 
        // $totalClients = count($clients);
        // $totalPages = ceil($totalClients / $limit);
    
        // // Récupérer les clients pour la page actuelle
        // $offset = ($page - 1) * $limit;
        // $clients = array_slice($clients, $offset, $limit);
    
        // return $this->render('client/index.html.twig', [
        //     'datas' => $clients,
        //     'currentPage' => $page,
        //     'totalPages' => $totalPages,
        //     'formSearch' => $formSearch->createView()
        // ]);
        $formSearch = $this->createForm(SearchClientType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            // Si le formulaire est soumis et valide, on cherche les clients avec le téléphone
            $clients = $clientRepository->findBy(['telephon' => $formSearch->get('telephon')->getData()]);
        } else {
            // Sinon, on récupère tous les clients
            $clients = $clientRepository->findAll();
        }

        $page = (int)$request->query->get('page', 1); // Page actuelle, par défaut 1
        $limit = 4; // Nombre d'éléments par page

        $totalClients = count($clients);
        $totalPages = ceil($totalClients / $limit);

        // Récupérer les clients pour la page actuelle
        $offset = ($page - 1) * $limit;
        $clients = array_slice($clients, $offset, $limit);

        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'formSearch' => $formSearch->createView(),
        ]);
    }
    
    
    /**
     * @Route("/clients/store", name="clients.store", methods={"GET", "POST"})
     */
    public function store(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $client = new Client(); // Création d'un nouvel objet Client
        
        
         $form = $this->createForm(ClientType::class, $client); // Création du formulaire pour le clientss

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
 * @Route("/clients/show/{id}", name="clients.show", methods={"GET"})
 */
public function show(int $id, ClientRepository $clientRepository): Response
{
    // Récupérer le client par son ID
    $client = $clientRepository->find($id);

    if (!$client) {
        throw $this->createNotFoundException('Client non trouvé');
    }

    // Filtrer les dettes soldées et non soldées
    $dettesSoldees = [];
    $dettesNonSoldees = [];

    foreach ($client->getDettes() as $dette) {
        if ($dette->isSoldee()) {
            $dettesSoldees[] = $dette;
        } else {
            $dettesNonSoldees[] = $dette;
        }
    }

    return $this->render('client/show.html.twig', [
        'client' => $client,
        'dettesSoldees' => $dettesSoldees,
        'dettesNonSoldees' => $dettesNonSoldees,
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
