<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'db.php';

if (isset($_POST['email'])) {

  $e = $con->real_escape_string($_POST['email']);

  $q = "SELECT id FROM users WHERE email=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("s",$e);

    $s->execute();

    $s->bind_result($i);

    $s->store_result();

    if ($s->num_rows > 0) {

      $s->close();

      $resetCode = substr(md5(uniqid(rand(), true)), 16, 16);
      $time = time();

      $insQ = "INSERT INTO resetRequests (userId, resetCode, time) VALUES (?, ?, ?)";

      if ($ins = $con->prepare($insQ)) {

        $ins->bind_param("isi",$i,$resetCode,$time);

        if ($ins->execute()) {

          $ins->close();
          
          $m = new PHPMailer;

          $m->setFrom('noreply@collectmemes.com', 'CollectMemes');

          $m->addReplyTo('support@collectmemes.com', 'CollectMemes');

          $m->addAddress($n);

          $m->Subject = 'CollectMemes Password Reset Request';

          $m->msgHTML("Thank you for submitting a password reset request. <a href='https://collectmemes.com/reset?v=" . $resetCode . "'>Set a new password by clicking here.</a> This reset request will be valid for 24 hours.");

          $m->send();

        }

      }

    } else {

      $s->close();

    }



  }

}

$con->close();
?>
