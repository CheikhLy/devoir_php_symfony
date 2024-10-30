<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\DetteType;
use App\Form\SearchDetteType;
use App\Repository\DetteRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dette;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Routing\Annotation\Route;

class DetteController extends AbstractController
{
    /**
     * @Route("/dettes", name="dettes.index", methods={"GET"})
     */
    public function index(DetteRepository $detteRepository, Request $request): Response
    {
        $form = $this->createForm(SearchDetteType::class);
        $form->handleRequest($request);

        $dettes = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $showOnlySolded = $form->get('status')->getData();

            if ($showOnlySolded) {
                $dettes = $detteRepository->findAll();
                $dettes = array_filter($dettes, fn(Dette $dette) => $dette->isSoldee());
            } else {
                $dettes = $detteRepository->findAll();
            }
        } else {
            $dettes = $detteRepository->findAll();
        }

        return $this->render('dette/index.html.twig', [
            'dettes' => $dettes,
            'form' => $form->createView(),
        ]);
    }
/**
     * @Route("/dettes/store", name="dettes.store", methods={"GET", "POST"})
     */
    public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $dette = new Dette(); 
        $form = $this->createForm(DetteType::class, $dette);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $dette->setCreateAt(new \DateTimeImmutable()); // Fixer la date de création
            $dette->setUpdateAt(new \DateTimeImmutable()); // Fixer la date de mise à jour

            $entityManager->persist($dette);
            $entityManager->flush();

            return $this->redirectToRoute('dettes.index');
        }

        return $this->render('dette/form.html.twig', [
            'formDette' => $form->createView(),
        ]);
    }
    /**
     * @Route("/dettes/{id}", name="dettes.getDetteByClient")
     */

    public function getDetteByClient(int $id, ClientRepository $clientRepository): Response
    {
        $client = $clientRepository->find($id);
        dd($client);
        return $this->render('dette/index.html.twig',[]);
    }

    
}
