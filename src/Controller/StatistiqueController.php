<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class StatistiqueController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(ManagerRegistry $reg): Response
    {
        $users = $reg->getRepository(User::class)->findAll();

        $data = [];
        foreach ($users as $user) {
            $age = $user->getAge();
            if (isset($data[$age])) {
                $data[$age]++;
            } else {
                $data[$age] = 1;
            }
        }

        return $this->render('statistique/index.html.twig', [
            'data' => $data
        ]);
    }
}

