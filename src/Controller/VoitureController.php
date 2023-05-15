<?php

namespace App\Controller;

use App\Entity\Sponsoring;
use App\Entity\Voiture;
use App\Entity\User;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\Serializer\SerializerInterface;

#[Route('/voiture')]
class VoitureController extends AbstractController


{
    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();

        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }
    #[Route('/JSON', name: 'app_voiture_indexJSON', methods: ['GET'])]
    public function indexJSON(VoitureRepository $voitureRepository, SerializerInterface $serializer): Response
    {
        $voitures = $voitureRepository->findAll();
        $jsonData = $serializer->serialize($voitures, 'json');
        return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    #[Route('/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function new(Request $request, VoitureRepository $voitureRepository): Response
    {

        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->save($voiture, true);

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }
    #[Route('/newjson', name: 'voiturenewJSON', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function newjson(Request $request,
                            ManagerRegistry $doctrine, VoitureRepository $voitureRepository, SerializerInterface $serializer): Response
    {
        $voiture = new Voiture();
        $idUser = 51 ;
        $repo = $doctrine->getRepository(User::class);
        $user = $repo->find($idUser);
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->save($voiture, true);

            // Sérialisation en JSON avec le Serializer
            $data = [
                'success' => true,
                'message' => 'Voiture created successfully.',
            ];
            $jsonData = $serializer->serialize($data, 'json');

            return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
    }






    #[Route('/pdf', name: 'app_voiture_pdf', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function generatePdf(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Récupération du contenu du template HTML
        $html = $this->renderView('voiture/pdf.html.twig', [
            'voitures' => $voitures
        ]);

        // Génération du PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Envoi du PDF en réponse HTTP
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="liste_voitures.pdf"');

        return $response;
    }

    #[Route('/{id}', name: 'app_voiture_show')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function show($id,ManagerRegistry $doctrine,EntityManagerInterface $entityManager): Response
    {

         $id= $doctrine->getRepository(Voiture::class)->find($id);
        return $this->render('voiture/show.html.twig', [
            'controller_name' => 'VoitureContoroller',
            'voiture' => $id,
        ]);
    }

    #[Route('/showjson/{id}', name: 'app_voiture_showJSON')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function showjson($id, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $voiture = $doctrine->getRepository(Voiture::class)->find($id);

        // Sérialisation en JSON avec le Serializer
        $data = [
            'controller_name' => 'VoitureController',
            'voiture' => $voiture,
        ];
        $jsonData = $serializer->serialize($data, 'json');

        return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}/edit', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function edit(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->save($voiture, true);

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editJSON', name: 'app_voiture_editJSON', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function editjson(Request $request, Voiture $voiture, VoitureRepository $voitureRepository, SerializerInterface $serializer): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->save($voiture, true);

            // Sérialisation en JSON avec le Serializer
            $data = [
                'success' => true,
                'message' => 'Voiture updated successfully.',
            ];
            $jsonData = $serializer->serialize($data, 'json');

            return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        // Récupérer les erreurs de validation du formulaire
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            if ($error instanceof FormError) {
                $errors[] = $error->getMessage();
            }
        }

        // Retourner les erreurs de validation sous forme de réponse JSON
        $data = [
            'success' => false,
            'message' => 'Invalid data',
            'errors' => $errors,
        ];
        $jsonData = $serializer->serialize($data, 'json');

        return new Response($jsonData, Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    #[Route('/deleteVoiture/{id}', name: 'app_voiture_delete')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $voiture = $doctrine->getRepository(Voiture::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($voiture);
        $em->flush();
        return $this->redirectToRoute('app_voiture_index');
    }

    #[Route('/deleteVoiturejson/{id}', name: 'app_voiture_deleteJSON')]
    public function deleteJSON($id,ManagerRegistry $doctrine, SerializerInterface $serializer){
        $voiture=$doctrine->getRepository(Voiture::class)->find($id);
        $rec=$doctrine->getManager();
        $rec->remove($voiture);
        $rec->flush();
        $jsonContent = $serializer->serialize($voiture, 'json' , ['groups' =>"events"]);
        return new Response("Voiture deleted successfully" );
    }
}