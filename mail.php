<?php
header('Content-Type: application/json');

$name = trim($_POST['contact-name']);
$phone = trim($_POST['contact-phone']);
$email = trim($_POST['contact-email']);
$subject = trim($_POST['subject']);
$message = trim($_POST['contact-message']);

$msg = [];

if ($name == "") {
    $msg['err'] = "Name cannot be empty!";
    $msg['field'] = "contact-name";
    $msg['code'] = false;
} else if ($phone == "") {
    $msg['err'] = "Phone number cannot be empty!";
    $msg['field'] = "contact-phone";
    $msg['code'] = false;
} else if (!preg_match("/^[0-9 \-\+]{4,17}$/", $phone)) {
    $msg['err'] = "Please enter a valid phone number!";
    $msg['field'] = "contact-phone";
    $msg['code'] = false;
} else if ($email == "") {
    $msg['err'] = "Email cannot be empty!";
    $msg['field'] = "contact-email";
    $msg['code'] = false;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $msg['err'] = "Please enter a valid email address!";
    $msg['field'] = "contact-email";
    $msg['code'] = false;
} else if ($message == "") {
    $msg['err'] = "Message cannot be empty!";
    $msg['field'] = "contact-message";
    $msg['code'] = false;
} else {
    $to = 'contact@example.com';
    $subjectLine = $subject ?: 'Contact Form Submission';

    $_message = '<html><body>';
    $_message .= '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>';
    $_message .= '<p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>';
    $_message .= '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>';
    $_message .= '<p><strong>Subject:</strong> ' . htmlspecialchars($subjectLine) . '</p>';
    $_message .= '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>';
    $_message .= '</body></html>';

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: $name <$email>\r\n";
    $headers .= "Cc: contact@example.com\r\n";
    $headers .= "Bcc: contact@example.com\r\n";

    if(mail($to, $subjectLine, $_message, $headers)){
        $msg['success'] = "Email has been sent successfully!";
        $msg['code'] = true;
    } else {
        $msg['err'] = "Failed to send email. Please try again later.";
        $msg['code'] = false;
    }
}

echo json_encode($msg);
