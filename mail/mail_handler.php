<?php
require_once('email_config.php');
require_once('phpmailer/PHPMailer/src/Exception.php');
require_once('phpmailer/PHPMailer/src/PHPMailer.php');
require_once('phpmailer/PHPMailer/src/SMTP.php');

$mail = new PHPMailer\PHPMailer\PHPMailer;
$mail->SMTPDebug = 0;           // Enable verbose debug output. Change to 0 to disable debugging output when using on live site.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = EMAIL_USER;   // SMTP username
$mail->Password = EMAIL_PASS;   // SMTP password
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = 'jen.icldaemon@gmail.com';  // sender's email address (shows in "From" field) //my server gmail
$mail->FromName = 'Jen Portfolio Daemon';   // sender's name (shows in "From" field) //Jen Portfolio Daemon
$mail->addAddress('jenniferlai.81@gmail.com', 'J Lai');  // Add a recipient (name is optional) //my real gmail
//$mail->addAddress('ellen@example.com');                        // Add a second recipient
$mail->addReplyTo($_POST['email']);        //$_POST['email']           // Add a reply-to address //the email the viewer person passed in, needs to be changed to a variable
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

function getUserIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$mail->Subject = 'Message from ' . $_POST['name'] . ' at ' . date("m/d/Y") . ' from IP:' . getUserIpAddr(); //'Message from ' . $_POST['name'] . ' at (datetime)'
$mail->Body    = "<div>
                    Name: {$_POST['name']}
                </div>
                <div>
                    Visitor Email: {$_POST['email']}
                </div>
                <div>
                    Subject: {$_POST['subject']}
                </div>
                <div>
                    Message: {$_POST['message']}
                </div>
                "; //html format of email <div>Subject: {$_POST['subject']}</div> etc. subject, name, email, message, ip address, date
$mail->AltBody = "Name: {$_POST['name']}, Visitor Email: {$_POST['email']}, Subject: {$_POST['subject']}, Message: {$_POST['message']}"; //strings of message

if (!$mail->send()) { //needs to change to print(json_encode(['success' => true]))
    print(json_encode([
        'success' => false,
        'message' => 'Message could not be sent.',
        // 'error' => $mail->ErrorInfo
    ]));
} else {
    print(json_encode([
        'success' => true,
        'message' => 'Message has been sent.'
    ]));
}
?>

<!--
    have a spinner waiting for message to send
    and after sending successfully, provide a success message
 -->
