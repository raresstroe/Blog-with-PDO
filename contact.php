<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

require 'includes/init.php';

$email = '';
$subject = '';
$message = '';
$sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $errors = [];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Please enter a valid email address';
    }

    if ($subject == '') {
        $errors[] = 'Please enter a subject';
    }

    if ($message == '') {
        $errors[] = 'Please enter a message';
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            //Server settings 
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = SMTP_HOST;                              //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = SMTP_USER;                              //SMTP username
            $mail->Password   = SMTP_PASS;                              //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('from@example.com');
            $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo($email);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            $sent = true;
        } catch (Exception $e) {
            $errors[] = $mail->ErrorInfo;
        }
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Contact</h2>

<?php if ($sent) : ?>
    <p>Message sent.</p>
<?php else : ?>

    <?php if (!empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" id="formContact">

        <div class="form-group">
            <label for="email">Your email</label>
            <input class="form-control" name="email" id="email" type="email" placeholder="Your email" value="<?= htmlspecialchars($email) ?>">
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input class="form-control" name="subject" id="subject" placeholder="Subject" value="<?= htmlspecialchars($subject) ?>">
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" name="message" id="message" placeholder="Message"><?= htmlspecialchars($message) ?></textarea>
        </div>

        <button class="btn">Send</button>

    </form>
<?php endif ?>



<?php require 'includes/footer.php'; ?>