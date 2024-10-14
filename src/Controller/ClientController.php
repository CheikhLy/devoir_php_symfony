<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ClientRepository;
use App\Form\ClientType;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
class ClientController extends AbstractController
{
    /**
     * @Route("/clients", name="clients.index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        // dd($clients);
        return $this->render('client/index.html.twig', [
            'datas' => $clients,
        ]);
    }
    /**
     * @Route("/clients/store", name="clients.store", methods={"GET", "POST"})
     */
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client(); // Création d'un nouvel objet Client
        $form = $this->createForm(ClientType::class, $client); // Création du formulaire pour le client

        $form->handleRequest($request); // Lancement de la requête HTTP et analyse des données envoyées par le client
        if ($form->isSubmitted()) {
            $client->setCreateAt(new \DateTimeImmutable()); // Fixer la date de création
            $client->setUpdateAt(new \DateTimeImmutable()); // Fixer la date de mise à jour
            $entityManager->persist($client);
            $entityManager->flush();
            return $this->redirectToRoute('clients.index');
        }
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
         /**
     * @Route("/clients/search/Telephone", name="clients.searchClientByTelephone", methods={"GET"})
     */
    public function searchClientByTelephone(Request $request): Response
    { 
    //query=>$_GET
    //request=>$_POST
    //  $request->query->get('key')=>$_GET['key'];
    //  $request->request->get('name_field')=>$_POST['name_field'];
        $telephone = $request->query->get('tel');
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
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
