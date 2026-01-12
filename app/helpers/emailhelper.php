<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class emailhelper
{
    /**
     * Sends an email using PHPMailer or Mailjet API
     *
     * @param array $settings SMTP settings
     * @param string $recipientEmail Email address of the recipient
     * @param string $subject Email subject
     * @param string $body Email body (HTML supported)
     * @return bool Returns true if email sent successfully, false otherwise
     */
    public static function sendEmail(array $settings, string $recipientEmail, string $subject, string $body): bool
    {
        try {
            return match ($settings['email_provider']) {
                "phpmailer" => self::sendWithPhpMailer($settings, $recipientEmail, $subject, $body),
                "mailjet" => self::sendWithMailjet($settings, $recipientEmail, $subject, $body),
                "symfony" => self::sendWithSymfony($settings, $recipientEmail, $subject, $body),
                default => throw new Exception('Email provider not supported.'),
            };
        } catch (Exception) {
            // Handle or log the exception appropriately
            return false;
        }
    }

    private static function sendWithPhpMailer(array $settings, string $recipientEmail, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $settings['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $settings['smtp_username'];
            $mail->Password = $settings['smtp_password'];
            $mail->SMTPSecure = $settings['smtp_encryption'];
            $mail->Port = $settings['smtp_port'];

            $mail->setFrom($settings['smtp_username'], $settings['sitename']);
            $mail->addAddress($recipientEmail);
            $mail->isHTML();
            $mail->Subject = $subject;
            $mail->Body = $body;

            return $mail->send();
        } catch (Exception) {
            return false;
        }
    }
    
    private static function sendWithMailjet(array $settings, string $recipientEmail, string $subject, string $body): bool
    {
        $mj = new Client($settings['mailjet_api_key'], $settings['mailjet_api_secret'], true, ['version' => 'v3.1']);

        $emailData = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $settings['smtp_username'],
                        'Name' => $settings['sitename']
                    ],
                    'To' => [
                        [
                            'Email' => $recipientEmail
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $body
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $emailData]);
        return $response->success();
    }

    private static function sendWithSymfony(array $settings, string $recipientEmail, string $subject, string $body): bool
    {
        try {
            $transport = new EsmtpTransport($settings['smtp_host'], $settings['smtp_port']);
            // Set authentication credentials
            $transport->setUsername($settings['smtp_username']);
            $transport->setPassword($settings['smtp_password']);
    
            $mailer = new Mailer($transport);
    
            // Create an instance of Email
            $email = (new Email())
                ->from(new Address($settings['smtp_username'], $settings['sitename']))
                ->to($recipientEmail)
                ->subject($subject)
                ->html($body);
    
            // Send the email
            $mailer->send($email);
    
            return true; // Email sent successfully
        } catch (\Exception $e) {
            // Handle or log the exception appropriately
            return false;
        }
    }
}
