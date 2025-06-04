<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "sipcraft4@gmail.com";
    $subject = "New Mug Order Request";

    $name = $_POST["name"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $body = "Name: $name\nContact: $contact\nEmail: $email\n\nMessage:\n$message";

    $file_tmp = $_FILES['artwork']['tmp_name'];
    $file_name = $_FILES['artwork']['name'];
    $file_type = $_FILES['artwork']['type'];
    $file_data = file_get_contents($file_tmp);
    $encoded_file = chunk_split(base64_encode($file_data));
    $boundary = md5("sipcraft");

    $headers = "From: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";

    $message_body = "--$boundary\r\n";
    $message_body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message_body .= $body . "\r\n\r\n";

    $message_body .= "--$boundary\r\n";
    $message_body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
    $message_body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
    $message_body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $message_body .= $encoded_file . "\r\n\r\n";
    $message_body .= "--$boundary--";

    if (mail($to, $subject, $message_body, $headers)) {
        echo "<script>alert('Order sent successfully!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Failed to send order.'); window.history.back();</script>";
    }
}
?>