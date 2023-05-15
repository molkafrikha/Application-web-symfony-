<?php

namespace App\Controller;

use App\Entity\Réponses;
use App\Form\RéponsesType;
use App\Repository\RéponsesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/r/ponses')]
class RéponsesController extends AbstractController
{
    #[Route('/', name: 'app_r_ponses_index', methods: ['GET'])]
    public function index(RéponsesRepository $réponsesRepository): Response
    {
        return $this->render('réponses/index.html.twig', [
            'r_ponses' => $réponsesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_r_ponses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RéponsesRepository $réponsesRepository): Response
    {
        $réponse = new Réponses();
        $form = $this->createForm(RéponsesType::class, $réponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $réponsesRepository->save($réponse, true);

            return $this->redirectToRoute('app_r_ponses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('réponses/new.html.twig', [
            'r_ponse' => $réponse,
            'form' => $form,
        ]);
    }

    #[Route('/{réponsesId}', name: 'app_r_ponses_show', methods: ['GET'])]
    public function show(Réponses $réponse): Response
    {
        return $this->render('réponses/show.html.twig', [
            'r_ponse' => $réponse,
        ]);
    }

    #[Route('/{réponsesId}/edit', name: 'app_r_ponses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Réponses $réponse, RéponsesRepository $réponsesRepository): Response
    {
        $form = $this->createForm(RéponsesType::class, $réponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $réponsesRepository->save($réponse, true);

            return $this->redirectToRoute('app_r_ponses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('réponses/edit.html.twig', [
            'r_ponse' => $réponse,
            'form' => $form,
        ]);
    }

    #[Route('/{réponsesId}', name: 'app_r_ponses_delete', methods: ['POST'])]
    public function delete(Request $request, Réponses $réponse, RéponsesRepository $réponsesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$réponse->getRéponsesId(), $request->request->get('_token'))) {
            $réponsesRepository->remove($réponse, true);
        }

        return $this->redirectToRoute('app_r_ponses_index', [], Response::HTTP_SEE_OTHER);
    }
}
