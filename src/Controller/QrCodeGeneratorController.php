<?php

namespace App\Controller;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QrCodeGeneratorController extends AbstractController
{
    #[Route('/qr-codes', name: 'app_qr_codes')]
    public function index(): Response
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create('https://www.binaryboxtuts.com/')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
            $finfo = \finfo_open(\FILEINFO_MIME_TYPE);
           $mime_type = \finfo_file($finfo, 'images\download.png');
         \finfo_close($finfo);
          echo "The MIME type of the file is: $mime_type";
         
        $logo = Logo::create('images\download.png')
            ->setResizeToWidth(60);
        $label = Label::create('')->setFont(new NotoSans(8));
 
        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
        $qrCodes['simple'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('Simple')
                            )->getDataUri();
                            $qrCode->setForegroundColor(new Color(255, 0, 0));
                            $qrCodes['changeColor'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('Color Change')
                            )->getDataUri();
                     
                            $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
                            $qrCodes['changeBgColor'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('Background Color Change')
                            )->getDataUri();
                     
                            $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
                            $qrCodes['withImage'] = $writer->write(
                                $qrCode,
                                $logo,
                                $label->setText('With Image')->setFont(new NotoSans(20))
                            )->getDataUri();
                     
                            return $this->render('qr_code_generator/index.html.twig', $qrCodes);
                        }
}
