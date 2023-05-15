<?php

namespace App\Controller;
use App\Entity\Questions;
use App\Entity\Sondage;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use App\Repository\SondageRepository;
use App\Repository\ReponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


#[Route('/questions')]
class QuestionsController extends AbstractController
{
    #[Route('/{sondageId}', name: 'app_questions_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function index(QuestionsRepository $questionsRepository ,int $sondageId): Response
    {
        return $this->render('questions/index.html.twig', [
            'questions' => $questionsRepository->findAll(),
            'sondageId' =>$sondageId,
        ]);
    }

    #[Route('/new/{sondageId}', name: 'app_questions_new', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function new(Request $request, QuestionsRepository $questionsRepository,int $sondageId ,EntityManagerInterface $entityManager): Response
    {
        $question = new Questions();
        $sondage = $entityManager->getRepository(Sondage::class)->find($sondageId);
        $question ->setSondage($sondage);
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionsRepository->save($question, true);

            return $this->redirectToRoute('app_questions_index', ['sondageId' =>$sondageId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/new.html.twig', [
            'sondageId' =>$sondageId,
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{questionId}', name: 'app_questions_show', methods: ['GET'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{sondageId}/{questionId}/edit', name: 'app_questions_edit', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function edit(Request $request, Questions $question, QuestionsRepository $questionsRepository,int $sondageId ,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionsRepository->save($question, true);

            return $this->redirectToRoute('app_questions_index', ['sondageId' =>$sondageId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/edit.html.twig', [
            'sondageId' =>$sondageId,
            'question' => $question,
            'form' => $form,
        ]);
    }
      
    #[Route('/display/{sondageId}', name: 'app_sondage_display')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function listQuestion(QuestionsRepository $repo, $sondageId): Response
    {

       $questions = $repo->findById($sondageId);

        
        return $this->render('questions/index.html.twig', [
            'sondageId'=>$sondageId,
            'questions' => $questions,
            
        ]);
    }

   #[Route('/{sondageId}/{questionId}', name: 'app_questions_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function delete(Request $request, Questions $question, QuestionsRepository $questionsRepository,int $sondageId ,EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getQuestionId(), $request->request->get('_token'))) {
            $questionsRepository->remove($question, true);
        }

        return $this->redirectToRoute('app_questions_index', ['sondageId' => $sondageId], Response::HTTP_SEE_OTHER);
    }
    
    
    







}


