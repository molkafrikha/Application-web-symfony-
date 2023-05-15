<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Google\Client;
use App\Entity\Maintenance;
use App\Entity\Voiture;
use App\Form\MaintenanceType;
use App\Repository\VoitureRepository;
use App\Repository\MaintenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FlashBundle\FlashBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/maintenance')]

class MaintenanceController extends AbstractController
{


    #[Route('/', name: 'app_maintenance_index', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function index(MaintenanceRepository $maintenanceRepository): Response
    {
        $currentDate = new \DateTime('now');
        $flash = '';
        $maintenances = $maintenanceRepository->findAll();

        foreach ($maintenances as $maintenance) {
            $nextMaintenanceDate = $maintenance->getDatepassurance();

            if ($nextMaintenanceDate->format('Y-m-d') == $currentDate->format('Y-m-d')) {
                $flash = 'vous avez une voiture avec  la date de prochaine assurance est aujourd\'hui !';
                break;
            }
        }

        return $this->render('maintenance/index.html.twig', [
            'maintenances' => $maintenances,
            'flash' => $flash,
        ]);
    }






    #[Route('/export', name: 'app_maintenance_export', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function export(MaintenanceRepository $maintenanceRepository): Response
    {
        // Récupération des données
        $maintenances = $maintenanceRepository->findAll();

        // Création du classeur Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // En-têtes de colonnes
        $sheet->setCellValue('A1', 'date derniere assurance');
        $sheet->setCellValue('B1', 'date prochaine assurance');
        $sheet->setCellValue('C1', 'date derniere vidange');
        $sheet->setCellValue('D1', 'reste kilometrage ');
        $sheet->setCellValue('E1', 'matricule');


        // Lignes de données
        $row = 2;
        foreach ($maintenances as $maintenance) {
            $sheet->setCellValue('A' . $row, $maintenance->getDateDassurance());
            $sheet->setCellValue('B' . $row, $maintenance->getDatePAssurance());
            $sheet->setCellValue('C' . $row, $maintenance->getDateDvidange());
            $sheet->setCellValue('D' . $row, $maintenance->getRestekilometre());
            $sheet->setCellValue('E' . $row, $maintenance->getIdVoi()->getMatricule());
            $row++;
        }


        // Création du fichier Excel
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'export_') . '.xlsx';
        $writer->save($tempFile);

        // Envoi du fichier en réponse HTTP
        $response = new BinaryFileResponse($tempFile);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'liste_maintenances.xlsx');
        $response->deleteFileAfterSend(true);

        return $response;
    }




    #[Route('/new', name: 'app_maintenance_new', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function new(Request $request, MaintenanceRepository $maintenanceRepository, VoitureRepository $voitureRepository): Response
    {
        $maintenance = new Maintenance();

        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $voiture = $maintenance->getIdVoi();
            $kilometrage = $voiture->getKilometrage();
            $resteKilometre = 10000 - $kilometrage;
            $maintenance->setRestekilometre($resteKilometre);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $dateDAssurance = $maintenance->getDatedassurance();
            if (!empty($dateDAssurance)) {
                $datePAssurance = clone $dateDAssurance;
                $datePAssurance->modify('+1 year');
                $maintenance->setDatepassurance($datePAssurance);
            }
            $maintenanceRepository->save($maintenance, true);
            $this->addFlash('success', 'The maintenance was added successfully.');

            return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maintenance/new.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,

        ]);
    }

    #[Route('/{id_maintenance}', name: 'app_maintenance_show', methods: ['GET'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function show(Maintenance $maintenance): Response
    {
        return $this->render('maintenance/show.html.twig', [
            'maintenance' => $maintenance,
        ]);
    }

    #[Route('/{id_maintenance}/edit', name: 'app_maintenance_edit', methods: ['GET', 'POST'])]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function edit(Request $request, Maintenance $maintenance, MaintenanceRepository $maintenanceRepository): Response
    {
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maintenanceRepository->save($maintenance, true);

            return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maintenance/edit.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('deleteMaintenance/{id_maintenance}', name: 'app_maintenance_delete')]
    #[Security('not is_granted("ROLE_ADMIN") and is_granted("ROLE_USER")')]
    public function delete($id_maintenance,ManagerRegistry $doctrine): Response
    {
        $maintenance= $doctrine->getRepository(Maintenance::class)->find($id_maintenance);
        $em=$doctrine->getManager();
        $em->remove($maintenance);
        $em->flush();
        return $this->redirectToRoute('app_maintenance_index');


    }


    public function maintenanceAction(DateTimeFormatter $formatter)
    {
        $now = new \DateTime();
        $maintenances = $this->getDoctrine()->getRepository(Maintenance::class)->findAll();

        foreach ($maintenances as $maintenance) {
            $diff = $now->diff($maintenance->getDatePAssurance());
            $diffDays = (int) $diff->format('%r%a');
            if ($diffDays === 0) {
                $this->addFlash('warning', sprintf('La date de prochaine maintenance pour la voiture %s est la même que la date système !', $maintenance->getIdVoi()->getMatricule()));
            }
        }

        // ...
    }

}
