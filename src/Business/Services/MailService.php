<?php
/**
 * EHS
 *
 * @category  EHS
 * @package   TheFeed\Business\Services
 * @author    Mehdi Sahari <mesah@smile.fr>
 * @copyright 2023 Smile
 */
namespace TheFeed\Business\Services;
use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    private $mailer;
    private $fromEmail;
    private $fromName;

    public function __construct($mailer, $fromEmail, $fromName)
    {
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    public function sendMail($to, $subject, $body, $attachments = [])
    {
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // Set mailer to use SMTP
            $mail->isSMTP();
            $mail->SMTPAuth = true;

            // Set SMTP settings
            $mail->Host = 'smtp.example.com'; // Your SMTP server
            $mail->SMTPSecure = 'tls'; // Encryption method
            $mail->Port = 587; // Port number
            $mail->Username = 'your_username@example.com'; // SMTP username
            $mail->Password = 'your_password'; // SMTP password

            // Set sender and recipient
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($to);

            // Set email subject and body
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Add attachments
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment);
            }

            // Send the email
            $mail->send();

        } catch (Exception $e) {
            throw new ServiceException("Failed to send email: " . $mail->ErrorInfo);
        }
    }
}