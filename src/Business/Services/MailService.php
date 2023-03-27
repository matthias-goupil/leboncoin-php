<?php
namespace TheFeed\Business\Services;

class MailService
{
    private $fromEmail;
    private $fromName;

    public function __construct($fromEmail, $fromName)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    public function sendMail($to, $subject, $body, $attachments = [])
    {
        $headers = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $attachmentContent = file_get_contents($attachment);
                $attachmentContent = chunk_split(base64_encode($attachmentContent));
                $attachmentName = basename($attachment);
                $headers .= "Content-Type: application/octet-stream; name=\"{$attachmentName}\"\r\n";
                $headers .= "Content-Transfer-Encoding: base64\r\n";
                $headers .= "Content-Disposition: attachment; filename=\"{$attachmentName}\"\r\n\r\n";
                $headers .= "{$attachmentContent}\r\n\r\n";
            }
        }

        $success = mail($to, $subject, $body, $headers);

        if (!$success) {
            error_log("Failed to send email to {$to}");
            return false;
        }

        return true;
    }
}

