<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ForgotPasswordType;





class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]

    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse

    {
        return $this->redirectToRoute("app_login");
    }

    #[Route(path: '/forgot', name: 'app_forgot')]
    public function forgotPassword(Request $request, UserRepository $user, MailerInterface $mailer, TokenGeneratorInterface $tokenGeneratorInterface , ManagerRegistry $reg )

    {

        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();


            $user = $user->findOneBy(['email'=>$donnees]);
            if(!$user) {
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("forgot");

            }
            $token = $tokenGeneratorInterface->generateToken();

            try{
                $user->setresettoken($token);
                $entityManger =$reg->getManager();
                $entityManger->persist($user);
                $entityManger->flush();




            }catch(\Exception $exception) {
                $this->addFlash('warning','une erreur est survenue :'.$exception->getMessage());
                return $this->redirectToRoute("app_login");


            }

            $url = $this->generateUrl('app_reset',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);

            //BUNDLE MAILER
            $message = (new Email())
                ->From('aicha.nciri@esprit.tn')
                ->To($user->getEmail())
                ->text("<p> Bonjour</p> une demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant :".$url,
                    "text/html");

            //send mail
            $mailer->send($message);
            $this->addFlash('message','E-mail  de réinitialisation du mp envoyé :');
            //    return $this->redirectToRoute("app_login");



        }

        return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);
    }
    #[Route(path: '/reset/{token}', name: 'app_reset')]
    public function resetpassword(Request $request,string $token =null, UserPasswordHasherInterface  $passwordEncoder, ManagerRegistry $registry)
    {
        $user = $registry->getRepository(User::class)->findOneBy(['reset_token'=>$token]);

        if($user == null ) {
            $this->addFlash('danger','TOKEN INCONNU');
            return $this->redirectToRoute("app_login");

        }

        if($request->isMethod('POST')) {
            $reset_token = $user->getresettoken();
            if ($reset_token !== null) {
                // do something with $reset_token
            }
            $user->setresettoken(null);

            $user->setPassword($passwordEncoder->hashPassword($user,$request->request->get('password')));
            $entityManger = $registry->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Mot de passe mis à jour :');
            return $this->redirectToRoute("app_login");

        }


        else {
            return $this->render("security/resetPassword.html.twig",['token'=>$token]);

        }
    }


}