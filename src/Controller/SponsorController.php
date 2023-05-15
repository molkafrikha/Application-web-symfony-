<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterfaceTrait;
use Symfony\UX\Chartjs\Model\Chart;
use League\Csv\Writer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Sponsoring;
use App\Form\SponsorType;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\SponsoringRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SponsorController extends AbstractController
{


// ...

#[Route('/sponsor', name: 'app_sponsor')]
#[Security('is_granted("ROLE_ADMIN")')]
    public function index(ManagerRegistry $doctrine, ChartBuilderInterface $chartBuilder): Response
    {
        $sponsors = $doctrine->getRepository(Sponsoring::class)->findAll();

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);


        return $this->render('sponsor/index.html.twig', [
            'controller_name' => 'SponsorController',
            'sponsors' => $sponsors,
            'chart' => $chart,
           // 'totalMontant' => $totalMontant,
        ]);
    }

    #[Route('/deletesponsor/{id}', name: 'delete_sponsor')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function deleteSponsor($id,ManagerRegistry $doctrine){
        $sponsor=$doctrine->getRepository(Sponsoring::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($sponsor);
        $em->flush();
        return $this->redirectToRoute('app_sponsor');
    }
    #[Route('/addsponsor', name: 'add_sponsor')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function add(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $sponsor = new Sponsoring();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($sponsor);
            $entityManager->flush();
            $email = (new Email())
                ->from('waelbenyoussef19991@gmail.com')
                ->to($sponsor->getEmail())
                ->subject('Cher Nouveau Sponsor Inscrit '.$sponsor->getSponsor())
                ->html("<html>\n" .
                    "    <body style=\"background-color: #7035a1;\">\n" .
                    "        <table>\n" .
                    "        <tr><td><img src=\"https://scontent.ftun9-1.fna.fbcdn.net/v/t39.30808-6/332874625_548243220619858_8178574408692685316_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=8bfeb9&_nc_ohc=svtSh4t2JhYAX_Njqmn&_nc_ht=scontent.ftun9-1.fna&oh=00_AfAs6cXw9OhnJJ4lHsgYDPxdGeQAZDAQD3tJhNWQF-rHpQ&oe=63FF4652\" width=\"100px\" length=\"100px\"></td>\n" .
                    "        <td><h1 style=\"color:#fd8735\">Bonjour notre nouveau Sponsor </h1></td></tr>\n" .
                    "        </table>\n" .
                    "        <p style=\"color:#fd8735\">Bonjour Monsieur/Madame,</p>\n" .
                    "        <p style=\"color:#fd8735\">Votre Société ".$sponsor->getSponsor()." est ajoutée à notre base de données des sponsors des évènements.A prés la signature du contrat en ". $sponsor->getDatesignature()->format('Y-m-d') ."</p>\n" .
                    "        <p style=\"color:#fd8735\">Merci pour votre charité de ".$sponsor->getMontant()." DT et Au revoir !</p>\n" .
                    "    </body>\n" .
                    "</html>","text/html");

            //$mailer->send($email);

            return $this->redirectToRoute('app_sponsor');
        }
        return $this->render('sponsor/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/modifSponsor/{id}', name: 'modif_sponsor')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function modif($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $sponsor =  $doctrine->getRepository(Sponsoring::class)->find($id);
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_sponsor');
        }
        return $this->render('sponsor/modifier.html.twig', [
            'form' => $form->createView(),
            'sponsor'=>$sponsor
        ]);
    }
    #[Route('/sponsordetail/{id}', name: 'detail_sponsor')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function detail($id,ManagerRegistry $doctrine,EntityManagerInterface $entityManager): Response
    {
        $sponsor =  $doctrine->getRepository(Sponsoring::class)->find($id);
        $evenments = $doctrine->getRepository(Sponsoring::class)->getEvenementBySponsorId($entityManager, $id);
        return $this->render('sponsor/details.html.twig', [
            'controller_name' => 'SponsorController',
            'sponsor'=>$sponsor,
            'events'=>$evenments
        ]);
    }

    /*#[Route('/searchSponsorx', name:'searchSponsor')]
    public function searchSponsor(Request $request,ManagerRegistry $doctrine,NormalizerInterface $Normalizer,SponsoringRepository $sr)
    {

        $requestString=$request->get('searchValue');
        $sponsorings=$sr->getSponsorbyNom($requestString);
        $jsonContent = $Normalizer->normalize($sponsorings,'json',['groups'=>'sponsorings']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }
*/
    #[Route('/search', name:'searchSponsor')]
    public function search(Request $request, SponsoringRepository $sr, SerializerInterface $serializer)
    {
        $query = $request->query->get('query');
        $sponsors = $sr->createQueryBuilder('sponsoring')
            ->where('sponsoring.sponsor LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
        $json = $serializer->serialize($sponsors, 'json');
        return new JsonResponse($json);
    }
    #[Route('/generate-pdf', name:'generate_pdf')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function generatePdf(ManagerRegistry $doctrine)
    {

        $sponsors = $doctrine->getRepository(Sponsoring::class)->findAll();
         $html = $this->renderView('sponsor/tableau.html.twig', [
            'sponsors'=>$sponsors
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="FiThnitek - Liste des Sponsors.pdf"');
        return $response;
    }
    #[Route('/generate-csv', name:'generate_csv')]
    #[Security('is_granted("ROLE_ADMIN")')]
    public function generateCsv(ManagerRegistry $doctrine)
    {
        $sponsors = $doctrine->getRepository(Sponsoring::class)->findAll();
        $csvData = [];
        $csvData[] = ['Nom du sponsor', 'Montant','Pourcentage de don'];
        $totalMontant = array_reduce($sponsors, function ($total, $sponsor) {
            return $total + $sponsor->getMontant();
        }, 0);

        foreach ($sponsors as $sponsor) {
            $pourcentage = ($sponsor->getMontant() / $totalMontant) * 100;
            $csvData[] = [$sponsor->getSponsor(), $sponsor->getMontant(),$pourcentage.'%'];
        }
        $csvData[] = ['Total', $totalMontant];
        $csv = Writer::createFromString('');
        $csv->insertAll($csvData);
        $csvContent = $csv->getContent();
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="FiThnitek - Liste des Sponsors.csv"');

        return $response;
    }
    #[Route('/chart', name: 'chart')]
    public function chart(ManagerRegistry $doctrine, ChartBuilderInterface $chartBuilder)
    {
        $sponsors = $doctrine->getRepository(Sponsoring::class)->findAll();
        $totalMontant = array_reduce($sponsors, function ($total, $sponsor) {
            return $total + $sponsor->getMontant();
        }, 0);

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

        $chart->setData([
            'labels' => array_map(function ($sponsor) {
                return $sponsor->getSponsor();
            }, $sponsors),
            'datasets' => [
                [
                    'label' => 'Montants des sponsors',
                    'data' => array_map(function ($sponsor) use ($totalMontant) {
                        return round(($sponsor->getMontant() / $totalMontant) * 100, 2);
                    }, $sponsors),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#2ecc71',
                        '#3498db'
                    ],
                ],
            ],
        ]);

        $chart->setOptions([
            'title' => [
                'display' => true,
                'text' => 'Pourcentage de contribution des sponsors',
            ],
        ]);

        return $this->render('chart.html.twig', [
            'chart' => $chart,
        ]);
    }
}
