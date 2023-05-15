<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Avis;
use App\Form\AvisType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AvisRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function index(Request $request,ManagerRegistry $doctrine,AvisRepository $avisRepository ,PaginatorInterface $paginator): Response
    {
        $repo = $doctrine->getRepository(Avis::class);
        $Avis = $repo->findAll();
        $nombreAvis = $avisRepository->countAvis();
        $pagination = $paginator->paginate(
            $Avis,
            $request->query->getInt('page', 1),
            5
        );

    
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
            'Avis' => $Avis,
            'nombreAvis' => $nombreAvis,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/addavis', name: 'add_avis')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function add(Request $request, EntityManagerInterface $entityManager, Mailer $mailer,MailerInterface $mailer1): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
           
            $entityManager->persist($avis);
            $entityManager->flush();

            $mailer->sendEmail('niheleeroui124@gmail.com',"niheleeroui124@gmail.com", 'Nouveau avis', 'Un nouveau avis a été ajouté.',$mailer1);
            
            return $this->redirectToRoute('app_avis');
        }
        return $this->render('avis/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/avisdetail/{id}', name: 'detail_avis')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function detail($id,ManagerRegistry $doctrine,EntityManagerInterface $entityManager): Response
    {
        $avis =  $doctrine->getRepository(Avis::class)->find($id);
  
        return $this->render('avis/details.html.twig', [
            'controller_name' => 'AvisController',
            'Avis'=>$avis
        ]);
    }

    #[Route('/modifierAvis/{id}', name: 'modifier_Avis')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function modif($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $avis =  $doctrine->getRepository(Avis::class)->find($id);
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_avis');
        }
        return $this->render('avis/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/deleteavis/{id}', name: 'delete_avis')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function deleteAvis($id,ManagerRegistry $doctrine){
        $avis=$doctrine->getRepository(Avis::class)->find($id);
        $av=$doctrine->getManager();
        $av->remove($avis);
        $av->flush();
        return $this->redirectToRoute('app_avis');
    }

    #[Route('/searchAvis', name: 'search_avis')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function search(Request $request, ManagerRegistry $doctrine, AvisRepository $avisRepository ,PaginatorInterface $paginator): Response
    {
         $repo = $doctrine->getRepository(Avis::class);
         $query = $request->query->get('query');

        if (!$query) {
           $Avis = $repo->findAll();
        } else {
           $Avis = $repo->searchByQuery($query);
           $nombreAvis = $avisRepository->countAvis();
           $pagination = $paginator->paginate(
            $Avis,
            $request->query->getInt('page', 1),
            5
        );
    }
    return $this->render('avis/index.html.twig', [
        'controller_name' => 'AvisController',
        'Avis'=>$Avis,
        'nombreAvis' => $nombreAvis,
        'pagination' => $pagination,
    ]);
   }

   #[Route('/orderByCommentaire', name: 'tri_commentaire')]
   #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
   public function OrderCommentaire(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator,ManagerRegistry $doctrine)
  {
   $repo = $doctrine->getRepository(Avis::class);
   $Avis = $repo->findAll();
   $nombreAvis = $avisRepository->countAvis();
   $sort = $request->query->get('sort'); 

   $order = ($sort === 'asc') ? 'ASC' : 'DESC';

   $avis = $avisRepository->findByCommentaireAlphabetical($order);

   $pagination = $paginator->paginate(
       $avis,
       $request->query->getInt('page', 1),
       5
   );

   return $this->render('avis/index.html.twig', [
           'controller_name' => 'AvisController',
           'Avis'=>$Avis,
           'nombreAvis' => $nombreAvis,
           'pagination' => $pagination,
   ]);
  }


    #[Route('/avisAdmin', name: 'app_avisAdmin')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function indexAdmin(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Avis::class);
        $Avis = $repo->findAll();
        return $this->render('avis/consulterAdmin.html.twig', [
            'controller_name' => 'AvisController',
            'Avis' => $Avis,
        ]);
    }
}
