<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


#[Route('/bien')]
class BienController extends AbstractController
{
    #[Route('/aa', name: 'app_bien_index', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function index(BienRepository $bienRepository): Response
    {
        return $this->render('bien/index.html.twig', [
            'offres' => $bienRepository->findAll(),
        ]);
    }

    #[Route('/admin', name: 'app_bien_index1', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function index1(ManagerRegistry $doctrine): Response
    {

        $repo = $doctrine->getRepository(Bien::class);
        $biens = $repo->findAll();
        return $this->render('bien/indexadmin.html.twig', [
            'offres' => $biens,
        ]);
    }

    #[Route('/new', name: 'app_bien_new', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function new(Request $request, BienRepository $bienRepository): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_show', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/admin', name: 'app_bien_show1', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function show1(Bien $bien): Response
    {
        return $this->render('bien/showadmin.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_edit', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function edit(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_delete', methods: ['POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function delete(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $bienRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admindel/{id}', name: 'app_bien_delete1')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function delete1($id,ManagerRegistry $doctrine): Response
    {
        $bien=$doctrine->getRepository(Bien::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($bien);
        $em->flush();
        return $this->redirectToRoute('app_bien_index1');
    }

}
