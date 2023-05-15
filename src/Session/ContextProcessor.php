<?php

namespace App\Session;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\VarDumper\VarDumper;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

class ContextProcessor //CLASS THAT SUPPLEMENTS ADDITIONAL INFORMATION TO TEMPLATES AUTOMATICALLY
{
    private $security;
    private $requestStack;
    private $entityManager;

    public function __construct(
        Security $security, 
        RequestStack $rs, 
        EntityManagerInterface $entityManager
        )
    {
        $this->security = $security;
        $this->requstStack = $rs;
        $this->entityManager = $entityManager;
    }

    public function current_user(){
        $session = $this->requstStack->getSession();
        if($session->get("current_user")){
            return $session->get("current_user");
        } else {
            return null;
        }
    }
}
?>