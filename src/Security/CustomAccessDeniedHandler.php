<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;

class CustomAccessDeniedHandler implements AccessDeniedHandlerInterface
{
private $twig;

public function __construct(Environment $twig)
{
$this->twig = $twig;
}

public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
{
// Customize your response here
$content = $this->twig->render('access_denied.html.twig');
return new Response($content, Response::HTTP_FORBIDDEN);
}
}
