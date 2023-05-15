<?php

namespace App\Controller;

use App\Entity\Demandecovoiturage;
use App\Form\DemandecovoiturageType;
use App\Repository\DemandecovoiturageRepository;
use App\Repository\OffrecovoiturageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/demandecovoiturage')]
class DemandeController extends AbstractController
{
    #[Route('/', name: 'app_demandecovoiturage_index', methods: ['GET', 'POST'])]
    
    public function index(DemandecovoiturageRepository $dr): Response
    {
        $d = $dr->findAll();
        $a = [];
        foreach($d as $u){
            array_push($a, $u);
        }
        return $this->render('demandecovoiturage/demande-index.html.twig', ['demandes' => $d]);
    }

    #[Route('/offre', name: 'pageOffre', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function pageOffre(OffrecovoiturageRepository $dr): Response
    {
        $d = $dr->findAll();
        return $this->render('demandecovoiturage/offre-index.html.twig', [
            'offres' => $d,
        ]);
    }

    #[Route('/liste', name: 'app_demandecovoiturage_liste', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]

    public function listeDemandes(DemandecovoiturageRepository $dr,RequestStack $rs ): Response
    {
        $d = $dr->findAll();
        $a=[];
       foreach($d as $demande){
           /* if ($rs->getSession()->get("current_user")->getId() == $demande->getIdUser()){
                $demande->setUser($rs->getSession()->get("current_user"));
                array_push($a,$demande);
            }*/
        }
        //$a = [];
        foreach($d as $u){
            array_push($a, $u);
        }
        return $this->render('demandecovoiturage/demande-index.html.twig', ['demandes' => $d]);
    }

    #[Route('/search', name: 'offre_search', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]

    public function searchOff(
        DemandecovoiturageRepository $dr,
        RequestStack $rs, 
        EntityManagerInterface $em,
        Request $request
        ): Response
    {
        $qb = $em->createQueryBuilder();
        $qb->select("o")
        ->from("App\Entity\Offrecovoiturage", "o")
        ->where("o.lieud = :ld")
        ->andWhere("o.lieua = :la")
        ->setParameter("ld", $request->request->get("lieud"))
        ->setParameter("la", $request->request->get("lieua"));

        $o = $qb->getQuery()->getResult();
        return $this->render('demandecovoiturage/offre-res.html.twig', ['offres' => $o]);
    }
    #[Route('/supp', name: 'app_demandecovoiturage_supp', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]

    public function suppDemande(DemandecovoiturageRepository $dr, Request $request, EntityManagerInterface $em): Response
    {
        $d = $dr->find(intval($request->request->get("id-res")));
        if($d){
            $em->remove($d);
            $em->flush();
        }
        
        return new Response("/demandecovoiturage/liste");
    }

    #[Route('/reserver', name: 'app_offre_res', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function reserverOffre(
        OffrecovoiturageRepository $or,
        DemandecovoiturageRepository $dr, 
        Request $request,
        RequestStack $rs,
        MailerInterface $mailer,
        EntityManagerInterface $em

    ): Response
    {
        $curr_date = \DateTime::createFromFormat('Y-m-d', $request->request->get("dated"));
        //$curr_date = DateTime::createFromFormat('Y-m-d', $request->request->get("dated"));
        $offre = $or->findOneBy([
            'lieud' => $request->request->get("lieud"),
            'lieua' => $request->request->get("lieua"),
        ]);

        if($offre){
            $demande = new Demandecovoiturage();
           // $demande->setIdUser($rs->getSession()->get("current_user")->getId());
            $demande->setDatereservation($curr_date);
            $demande->setNbplace(intval($request->request->get("nb-p")));
            $demande->setIdOffre($offre);

            $email = (new Email())
            ->from('larbi.hiba@esprit.tn')
            ->to('waelbenyoussef19991@gmail.com')
            ->subject('Réservation')
            ->text('Votre réservation a été soumise avec succées.\nDATE: ' . $demande->getDatereservation()->format('Y-m-d H:i:s') . '\nNombre places: ' . $demande->getNbplace() . '\nOffre:' . $demande->getIdOffre()->getMatricule() )
            ->html('<p>Votre réservation a été soumise avec succées.</p><p>DATE: ' . $demande->getDatereservation()->format('Y-m-d H:i:s') . '</p><p>Nombre Places: ' . $demande->getNbplace() . '</p><p>Offre:' . $demande->getIdOffre()->getMatricule() . '</p>');

            //$mailer->send($email);

            $em->persist($demande);
            $em->flush();
        }
        return New Response("/demandecovoiturage/offre");
    }

    
/*
    #[Route('/new', name: 'app_demandecovoiturage_new', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function new(
        Request $request, 
        DemandecovoiturageRepository $demandecovoiturageRepository,
        MailerInterface $mailer
    ): Response
    {
        $demandecovoiturage = new Demandecovoiturage();
        $form = $this->createForm(DemandecovoiturageType::class, $demandecovoiturage);
        $form->handleRequest($request);
        $d = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $demandecovoiturageRepository->save($demandecovoiturage, true);

            $email = (new Email())
            ->from('larbi.hiba@esprit.tn')
            ->to($d->getIdUser()->getLogin())
            ->subject('Réservation')
            ->text('Votre réservation a été soumise avec succées.\nDATE: ' . $d->getDatereservation()->format('Y-m-d H:i:s') . '\nNombre places: ' . $d->getNbplace() . '\nOffre:' . $d->getIdOffre()->getMatricule())
            ->html('<p>Votre réservation a été soumise avec succées.</p><p>DATE: ' . $d->getDatereservation()->format('Y-m-d H:i:s') . '</p><p>Nombre Places: ' . $d->getNbplace() . '</p><p>Offre:' . $d->getIdOffre()->getMatricule() . '</p>');

            $mailer->send($email);

            return $this->redirectToRoute('app_demandecovoiturage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demandecovoiturage/new.html.twig', [
            'demandecovoiturage' => $demandecovoiturage,
            'form' => $form,
        ]);
    }*/

    public function listOffre(DemandecovoiturageRepository $repo, $idOffre): Response
    {

       $questions = $repo->findById($idOffre);

        
        return $this->render('Demandecovoiturage/index.html.twig', [
            'Demandecovoiturage' => $Demandecovoiturage,
            
        ]);
    }

    #[Route('/{id}', name: 'app_demandecovoiturage_show', methods: ['GET'])]
    public function show(Demandecovoiturage $demandecovoiturage): Response
    {
        return $this->render('demandecovoiturage/show.html.twig', [
            'demandecovoiturage' => $demandecovoiturage,
        ]);
    }

    /*#[Route('/{id}/edit', name: 'app_demandecovoiturage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demandecovoiturage $demandecovoiturage, DemandecovoiturageRepository $demandecovoiturageRepository): Response
    {
        $form = $this->createForm(DemandecovoiturageType::class, $demandecovoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandecovoiturageRepository->save($demandecovoiturage, true);

            return $this->redirectToRoute('app_demandecovoiturage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demandecovoiturage/edit.html.twig', [
            'demandecovoiturage' => $demandecovoiturage,
            'form' => $form,
        ]);
    }*/

    #[Route('/{id}', name: 'app_demandecovoiturage_delete', methods: ['POST'])]
    public function delete(Request $request, Demandecovoiturage $demandecovoiturage, DemandecovoiturageRepository $demandecovoiturageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandecovoiturage->getId(), $request->request->get('_token'))) {
            $demandecovoiturageRepository->remove($demandecovoiturage, true);
        }

        return $this->redirectToRoute('app_demandecovoiturage_index', [], Response::HTTP_SEE_OTHER);
    }

   
    }
  





 
    


