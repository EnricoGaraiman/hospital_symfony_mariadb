<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailServices
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($to, $subject, $template, $context)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('enrico.healthy.clinic@gmail.com', 'Enrico Healthy Clinic'))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'context' => $context,
            ])
        ;
        try{
            $this->mailer->send($email);
        }
        catch (\Exception $exception) {
            throw new \Exception('Error when send email: ' . $exception->getMessage());
        }
    }


}