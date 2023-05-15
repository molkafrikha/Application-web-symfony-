<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/agence')]
class AgenceController extends AbstractController
{
    #[Route('/aa', name: 'app_agence_indexuser', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function indexuser(AgenceRepository $agenceRepository): Response
    {
        return $this->render('agence/indexuser.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_agence_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function index(AgenceRepository $agenceRepository): Response
    {
        return $this->render('agence/index.html.twig', [
            'agences' => $agenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_agence_new', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function new(Request $request, AgenceRepository $agenceRepository): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenceRepository->save($agence, true);

            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agence/new.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agence_show', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function show(Agence $agence): Response
    {
        return $this->render('agence/show.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agence_edit', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function edit(Request $request, Agence $agence, AgenceRepository $agenceRepository): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agenceRepository->save($agence, true);

            return $this->redirectToRoute('app_agence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agence/edit.html.twig', [
            'agence' => $agence,
            'form' => $form,
        ]);
    }

    #[Route('deleteVoiture/{id}', name: 'app_agence_delete')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function delete($id,ManagerRegistry $doctrine): Response
    {
        $agence= $doctrine->getRepository(Agence::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($agence);
        $em->flush();
        return $this->redirectToRoute('app_agence_index');


    }
}
