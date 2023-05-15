<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Repository\SponsoringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Relation1;
use App\Form\Relation1Type;
use Symfony\Component\Serializer\SerializerInterface;
use Pyrrah\OpenWeatherMapBundle\Services\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Evenement::class);
        $Events = $repo->findAll();
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
            'events'=>$Events
        ]);
    }
    #[Route('/evenements', name: 'app_evenement2')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function index1(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Evenement::class);
        $Events = $repo->findAll();
        return $this->render('evenement/indexUser.html.twig', [
            'controller_name' => 'EvenementController',
            'events'=>$Events
        ]);
    }

    #[Route('/addevent', name: 'add_event')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($evenement);
            $entityManager->flush();
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render('evenement/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/deleteevenement/{id}', name: 'delete_evenement')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function deleteEvenement($id,ManagerRegistry $doctrine){
        $evenement=$doctrine->getRepository(Evenement::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('app_evenement');
    }
    #[Route('/modifEvenement/{id}', name: 'modif_evenement')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function modif($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $evenement =  $doctrine->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render('evenement/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/evenementdetail/{id}', name: 'detail_event')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function detail(HttpClientInterface $httpClient,Request $request,$id,ManagerRegistry $doctrine,EntityManagerInterface $entityManager,Client $client,SerializerInterface $serializer): Response
    {
        $evenement =  $doctrine->getRepository(Evenement::class)->find($id);
        $ville = $evenement->getLieu();
        $response = $client->getWeather($ville);
        //$response = $client->query('weather', array('q' => 'Paris,fr'));
        $weatherData = json_decode(json_encode($response), true);
        $weather = $weatherData['weather'];
        $response1 = $httpClient->request('GET', 'http://api.openweathermap.org/data/2.5/weather?q='.$ville.'&appid=5b491eb9b69dd529d5cb765278c52609&units=metric&lang=fr');
        $content1 = $response1->getContent();
        $weatherData1 = json_decode($content1, true);
        $weather1 = $weatherData1['weather'];

        $json = $serializer->serialize($weather, 'json');
        $sponsors = $doctrine->getRepository(Evenement::class)->getSponsorByEvenementId($entityManager, $id);
        $relation = new Relation1();
        $form = $this->createForm(Relation1Type::class, $relation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $relation->setIdEvenement($evenement);
            $entityManager->persist($relation);
            $entityManager->flush();
            return $this->redirectToRoute('detail_event', ['id' => $id]);
        }
      return $this->render('evenement/details.html.twig', [
            'controller_name' => 'EvenementController',
            'events'=>$evenement,
          'sponsors'=>$sponsors,
          'weather' => $weather,
          'weather_data' => $weatherData1,
          'form'=>$form->createView()
      ]);
    }
    #[Route('/evenemetdetail/{id}', name: 'detail_event1')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function detail1(HttpClientInterface $httpClient,Request $request,$id,ManagerRegistry $doctrine,EntityManagerInterface $entityManager,Client $client,SerializerInterface $serializer): Response
    {
        $evenement =  $doctrine->getRepository(Evenement::class)->find($id);
        $ville = $evenement->getLieu();
        $response = $client->getWeather($ville);
        //$response = $client->query('weather', array('q' => 'Paris,fr'));
        $weatherData = json_decode(json_encode($response), true);
        $weather = $weatherData['weather'];
        $response1 = $httpClient->request('GET', 'http://api.openweathermap.org/data/2.5/weather?q='.$ville.'&appid=5b491eb9b69dd529d5cb765278c52609&units=metric&lang=fr');
        $content1 = $response1->getContent();
        $weatherData1 = json_decode($content1, true);
        $weather1 = $weatherData1['weather'];

        $json = $serializer->serialize($weather, 'json');
        $sponsors = $doctrine->getRepository(Evenement::class)->getSponsorByEvenementId($entityManager, $id);
        $relation = new Relation1();
        $form = $this->createForm(Relation1Type::class, $relation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $relation->setIdEvenement($evenement);
            $entityManager->persist($relation);
            $entityManager->flush();
            return $this->redirectToRoute('detail_event', ['id' => $id]);
        }
        return $this->render('evenement/detailsUser.html.twig', [
            'controller_name' => 'EvenementController',
            'events'=>$evenement,
            'sponsors'=>$sponsors,
            'weather' => $weather,
            'weather_data' => $weatherData1,
            'form'=>$form->createView()
        ]);
    }
    #[Route('/addspsrtoevent', name: 'add_spsr_to_event')]
    public function addsps(Request $request, EntityManagerInterface $entityManager): Response
    {
        $relation = new Relation1();
        $form = $this->createForm(Relation1Type::class, $relation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($relation);
            $entityManager->flush();
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render('evenement/affecter.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/trievent', name:'trievent')]
    public function trier(Request $request, EvenementRepository $er, SerializerInterface $serializer)
    {
        $events = $er->findByParticipants();
        $json = $serializer->serialize($events, 'json');
        return new JsonResponse($json);
    }
    #[Route('/trieventa', name:'trieventa')]
    public function triera(Request $request, EvenementRepository $er, SerializerInterface $serializer)
    {
        $events = $er->findByParticipantsA();
        $json = $serializer->serialize($events, 'json');
        return new JsonResponse($json);
    }
    /*public function details(HttpClientInterface $httpClient, SerializerInterface $serializer, $id)
    {
        $response = $httpClient->request('GET', 'http://api.openweathermap.org/data/2.5/weather?q=Paris&appid=your_api_key');
        return new JsonResponse($json);
    }*/
    #[Route("/AllEventsJson", name: "listEventsjdsoche")]
    public function indexAdminJSON(ManagerRegistry $doctrine, SerializerInterface $serializer)
    {
        $repo = $doctrine->getRepository(Evenement::class);
        $events = $repo->findAll();
        $json = $serializer->serialize($events, 'json' , ['groups' =>"events"]);
        return new Response($json);
    }

    #[Route('/evenmentdetailJSON/{id}', name: 'detailevenementJSON')]
    public function detailJSON($id,ManagerRegistry $doctrine,SerializerInterface $serializer)
    {
        $evenement =  $doctrine->getRepository(Evenement::class)->find($id);
        $jsondetails = $serializer->serialize($evenement, 'json' , ['groups' =>"events"]);
        return new Response($jsondetails);
    }

    #[Route('/addevenementJSON', name: 'addevenementhdgzh')]
    public function addJSON(Request $request,ManagerRegistry $doctrine, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $evenement = new Evenement();

        $evenement->setLieu($request->get('lieu'));
        $evenement->setDate(new \DateTime());
        $evenement->setDatefin(new \DateTime());
        $evenement->setTitre($request->get('titre'));
        $evenement->setDescription($request->get('description'));
        $evenement->setNbparticipants(0);

        $entityManager->persist($evenement);
        $entityManager->flush();

        $json = $serializer->serialize($evenement, 'json' , ['groups' =>"events"]);
        return new Response($json);
    }
    #[Route('/modifierEveJSON/{id}', name: 'modifierJSONEvenet')]
    public function modifJSON($id, EntityManagerInterface $entityManager,ManagerRegistry $doctrine ,Request $request, SerializerInterface $serializer)
    {
        $reclamation =  $doctrine->getRepository(Evenement::class)->find($id);
        $evenement = new Evenement();
        $reclamation->setLieu($request->get('lieu'));
        $reclamation->setDate(new \DateTime($request->get('date')));
        $reclamation->setDatefin(new \DateTime($request->get('datefin')));
        $reclamation->setTitre($request->get('titre'));
        $reclamation->setDescription($request->get('description'));
        $reclamation->setNbparticipants($request->get('nbparticipants'));
        $entityManager->flush();


        $jsonContent = $serializer->serialize($evenement, 'json' , ['groups' =>"events"]);
        return new Response("Evénement a été modifié ");
    }
    #[Route('/deletereventJSON/{id}', name: 'deleteeventJSON')]
    public function deleteJSON($id,ManagerRegistry $doctrine, SerializerInterface $serializer){
        $evenement=$doctrine->getRepository(Evenement::class)->find($id);
        $rec=$doctrine->getManager();
        $rec->remove($evenement);
        $rec->flush();
        $jsonContent = $serializer->serialize($evenement, 'json' , ['groups' =>"events"]);
        return new Response("Event deleted successfully" );
    }


}
