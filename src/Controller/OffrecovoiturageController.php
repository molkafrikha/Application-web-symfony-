<?php

namespace App\Controller;

use App\Entity\Offrecovoiturage;
use App\Form\OffrecovoiturageType;
use App\Repository\OffrecovoiturageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Geocoder\Exception\Exception;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\GeocodeQueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Twilio\Rest\Client;

#[Route('/offrecovoiturage')]
class OffrecovoiturageController extends AbstractController
{
    #[Route('/', name: 'app_offrecovoiturage_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function index(OffrecovoiturageRepository $offrecovoiturageRepository): Response
    {
        return $this->render('offrecovoiturage/indexadmin.html.twig', [
            'offres' => $offrecovoiturageRepository->findAll(),
        ]);
    }
    #[Route('/user', name: 'app_offrecovoiturage_index1', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function index1(OffrecovoiturageRepository $offrecovoiturageRepository): Response
    {
        return $this->render('offrecovoiturage/index.html.twig', [
            'offres' => $offrecovoiturageRepository->findAll(),
        ]);
    }
    #[Route('/geolocalisation', name: 'app_geolocalisation')]
    public function index2(): Response
    {
        return $this->render('geolocalisation/index.html.twig', [
            'controller_name' => 'GeolocalisationController',
        ]);
    }
    #[Route('/app-sms', name: 'app_sms', methods: ['POST','GET'])]
    public function sendSMS(Request $request)
    {
        $toPhoneNumber = $request->request->get('toPhoneNumber');

        $sid = 'ACdb7c4d594c9744a4b025ee3abd58fc19';
        $token = 'c1fc56312b92ece2deb4c16f10b98b43';
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            "+216".$toPhoneNumber,
            [
                'from' => '+12766002191',
                'body' => 'Bonjour, on va partir dans 30 min préparez-vous ',
            ]
        );


        return $this->redirectToRoute('offrecovoiturage_sms', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/new', name: 'app_offrecovoiturage_new', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function new(Request $request, OffrecovoiturageRepository $offrecovoiturageRepository): Response
    {
        $offrecovoiturage = new Offrecovoiturage();
        $form = $this->createForm(OffrecovoiturageType::class, $offrecovoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offrecovoiturageRepository->save($offrecovoiturage, true);

            return $this->redirectToRoute('app_offrecovoiturage_index1', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offrecovoiturage/new.html.twig', [
            'offrecovoiturage' => $offrecovoiturage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offrecovoiturage_show', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function show(Offrecovoiturage $offrecovoiturage): Response
    {
        return $this->render('offrecovoiturage/show.html.twig', [
            'offrecovoiturage' => $offrecovoiturage,
        ]);
    }

    #[Route('/{id}/admin', name: 'app_offrecovoiturage_show1', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function show1(Offrecovoiturage $offrecovoiturage): Response
    {
        return $this->render('offrecovoiturage/showadmin.html.twig', [
            'offrecovoiturage' => $offrecovoiturage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offrecovoiturage_edit', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function edit(Request $request, Offrecovoiturage $offrecovoiturage, OffrecovoiturageRepository $offrecovoiturageRepository): Response
    {
        $form = $this->createForm(OffrecovoiturageType::class, $offrecovoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offrecovoiturageRepository->save($offrecovoiturage, true);

            return $this->redirectToRoute('app_offrecovoiturage_index1', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offrecovoiturage/edit.html.twig', [
            'offrecovoiturage' => $offrecovoiturage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offrecovoiturage_delete', methods: ['POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function delete(Request $request, Offrecovoiturage $offrecovoiturage, OffrecovoiturageRepository $offrecovoiturageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offrecovoiturage->getId(), $request->request->get('_token'))) {
            $offrecovoiturageRepository->remove($offrecovoiturage, true);
        }

        return $this->redirectToRoute('app_offrecovoiturage_index1', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/admindel/{id}', name: 'app_offrecovoiturage_delete1')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function delete1($id,ManagerRegistry $doctrine): Response
    {
        $Offrecovoiturage=$doctrine->getRepository(Offrecovoiturage::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($Offrecovoiturage);
        $em->flush();

        return $this->redirectToRoute('app_offrecovoiturage_index');
    }

}

