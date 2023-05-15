<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{

    public function sendEmail(string $toEmail,string $fromEmail, string $subject, string $body,MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->text($body);

        $mailer->send($email);
    }
}