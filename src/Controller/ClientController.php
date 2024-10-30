<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SearchClientType;
use App\DTO\ClientSearchDto;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Dette;
use App\Form\DetteFiltrerType;
use App\Repository\DetteRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\enum\StatusDette;
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
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $clientSearchDto = new ClientSearchDto();
        $formSearch = $this->createForm(SearchClientType::class, $clientSearchDto);
        $formSearch->handleRequest($request);
        $page = $request->query->get('page', 1); // Récupérer la page actuelle, par défaut 1
        $limit = 4; // Nombre d'éléments par page
    
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            // Recherche avec pagination
            $clients = $clientRepository->findClientBy($clientSearchDto, $page, $limit);
            $count = count($clients); // Utiliser count si findClientBy retourne un tableau
        } else {
            // Récupérer tous les clients avec pagination
            $count = $clientRepository->countAllClients(); // Une méthode que vous devez définir dans le repository
            $clients = $clientRepository->paginateClients($page, $limit);
        }

        $maxPage = ceil($count / $limit);
    
        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'page' => $page,
            'maxPage' => $maxPage,
            'formSearch' => $formSearch->createView(),
        ]);
    }
            // $formSearch = $this->createForm(SearchClientType::class);
        // $formSearch->handleRequest($request);

        // if ($formSearch->isSubmitted() && $formSearch->isValid()) {
        //     // Si le formulaire est soumis et valide, on cherche les clients avec le téléphone
        //     $clients = $clientRepository->findBy(['telephon' => $formSearch->get('telephon')->getData()]);
        // } else {
        //     // Sinon, on récupère tous les clients
        //     $clients = $clientRepository->findAll();
        // }

        // $page = (int)$request->query->get('page', 1); // Page actuelle, par défaut 1
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
        //     'formSearch' => $formSearch->createView(),
        // ]);
    
    
    
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
 * @Route("/clients/show/{idClient}", name="clients.show", methods={"GET", "POST"})
 */
public function show(int $idClient, ClientRepository $clientRepository, Request $request, DetteRepository $detteRepository): Response
{
    $limit = 1;
    $page = $request->query->getInt('page', 1);

    $formSearch = $this->createForm(DetteFiltrerType::class);
    $formSearch->handleRequest($request);
    $client = $clientRepository->find($idClient);
    $status = $request->get("status", StatusDette::IMPAYE->value);
    if ($request->query->has('dette_filtrer')) {
        $status = $request->query->all('dette_filtrer')['status'];
    }
    $dettes = $detteRepository->findDetteByClient($idClient, $status, $limit, $page);

    $count = $dettes->count();
    $maxPage = ceil($count / $limit);
    return $this->render('client/dette.html.twig', [
        'dettes' => $dettes,
        'client' => $client,
        'status' => $status,
        'formSearch' => $formSearch->createView(),
        'page' => $page, // page actuelle
        'maxPage' => $maxPage,
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
