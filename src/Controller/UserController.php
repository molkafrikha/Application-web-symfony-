<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(User::class);
        $Users = $repo->findAll();
        return $this->render('user/show.html.twig', [
            'controller_name' => 'UserController',
            'users'=>$Users
        ]);
    }

    #[Route('/member', name: 'after_login')]
    public function indexMember(): Response
    {
        return $this->render('member/afterlogin.html.twig');
    }
    #[Route('/admin', name: 'admin')]
    public function indexAdmin(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/removeUser/{id}', name: 'remove_user')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function remove($id,ManagerRegistry $doctrine){
        $user=$doctrine->getRepository(User::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_user');
    }

   /* #[Route('/updateUser/{id}', name: 'update_user')]
    public function edit($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $user =  $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render('member/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }*/

    /**
     * @Route("/search/back", name="userajax", methods={"GET"})
     */
    public function searchouserajax(Request $request, UserController $userRepository): Response
     {
         $userRepository = $this->getDoctrine()->getRepository(User::class);
         $requestString = $request->get('searchValue');
         $User = $userRepository->finduserbynom($requestString);
 
         return $this->render('user/show.html.twig', [
             "users" => $User
         ]);
     }


    
}
