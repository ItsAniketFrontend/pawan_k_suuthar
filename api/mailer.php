<?php
require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Sends a new-enquiry notification email to LEAD_NOTIFY_EMAILS.
 * Returns true on success, false on failure. Never throws: email
 * delivery problems must not block saving the lead to the database.
 */
function send_lead_notification(array $lead): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_ENCRYPTION === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        foreach (LEAD_NOTIFY_EMAILS as $to) {
            $mail->addAddress($to);
        }
        $mail->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

        $mail->isHTML(true);
        $mail->Subject = 'New Apartment Interior Enquiry - ' . $lead['name'];
        $mail->Body = '
            <h2>New enquiry from the website</h2>
            <table cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-family:sans-serif;">
                <tr><td><strong>Name</strong></td><td>' . htmlspecialchars($lead['name']) . '</td></tr>
                <tr><td><strong>Phone</strong></td><td>' . htmlspecialchars($lead['phone']) . '</td></tr>
                <tr><td><strong>City</strong></td><td>' . htmlspecialchars($lead['city']) . '</td></tr>
                <tr><td><strong>Apartment Type</strong></td><td>' . htmlspecialchars($lead['apartment_type']) . '</td></tr>
                <tr><td><strong>Source</strong></td><td>' . htmlspecialchars($lead['source']) . '</td></tr>
            </table>
        ';
        $mail->AltBody = "New enquiry:\nName: {$lead['name']}\nPhone: {$lead['phone']}\nCity: {$lead['city']}\nApartment Type: {$lead['apartment_type']}\nSource: {$lead['source']}";

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        error_log('Lead notification email failed: ' . $e->getMessage());
        return false;
    }
}
