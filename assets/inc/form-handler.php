<?php
header('Content-Type: application/json');

require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$recipient = "business@thegreymatter.co";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['username']) ? strip_tags(trim($_POST['username'])) : "";
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : "";
    $phone = isset($_POST['phone']) ? strip_tags(trim($_POST['phone'])) : "";
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : "";
    $form_type = isset($_POST['form_type']) ? strip_tags(trim($_POST['form_type'])) : "Contact Form";

    if (empty($name) || empty($phone) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all fields correctly."]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rezaul.karim@cosmosgroup.com.bd';
        $mail->Password   = 'hqjd qdzq azpi doac'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rezaul.karim@cosmosgroup.com.bd', 'Greymatter Website');
        $mail->addAddress($recipient);
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(false);
        $mail->Subject = "Contact from Greymatter Website: " . $form_type;
        
        $email_content = "Form Type: $form_type\n";
        $email_content .= "Name: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Phone: $phone\n\n";
        $email_content .= "Message:\n$message\n";
        
        $mail->Body = $email_content;

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Thank you! Your message has been sent."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "There was a problem with your submission, please try again."]);
}
?>
