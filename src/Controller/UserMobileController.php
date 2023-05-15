<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UserMobileController extends AbstractController
{
    #[Route('/signUp/mobile', name: 'app_user_mobile')]
    public function signUp(Request $request )
    {
        $nom =$request ->query->get("nom");
      $prenom =$request->query->get("prenom");
      $email =$request->query->get("email");
     $password =$request->query->get("password");
     $roles =$request->query->get("roles");
     $age =$request->query->get("age");

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return new Response("email invalid");
     }
     $user = new User();
     $user->setNom($nom);
     $user->setPrenom($prenom);
     $user->setEmail($email);
     $user->setPassword($password);
     $user->setRoles(array($roles));
     $user->setAge($age);
try {
    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();
    return new JsonResponse("Account is created", 200);
}catch(\Exception $ex){
    return new JsonResponse(['error' => $ex->getMessage()], 500);


}
    }
#[Route('/login/mobile', name: 'login_user_mobile')]
public function loginM(Request $request )
    {
        $email =$request->query->get("email");
        $password =$request->query->get("password"); 
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->findOneBy(['email'=>$email]);
        if($user){
            if(password_verify($password,$user->getPassword())){
                $serializer = new Serializer([new ObjectNormalizer]);
                $formatted = $serializer->normalize($user);
                unset($formatted['password'],$formatted['resettoken']);
                return new JsonResponse($formatted);
               


            }
            else {
                return new Response("password not found");

            }
        }else{
            return new Response("user not found");
        }

    
}




}