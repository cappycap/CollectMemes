<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'db.php';

if (isset($_POST['email'])) {

  $e = $con->real_escape_string($_POST['email']);
  $i = "";

  $q = "SELECT id FROM users WHERE email='" . $e . "'";

  if ($s = $con->prepare($q)) {

    $s->execute();

    $s->bind_result($uId);

    $s->store_result();

    if ($s->fetch()) {

      $i = $uId;

      if ($s->num_rows > 0) {

        $s->close();

        $resC = "SELECT id FROM resetRequests WHERE userId=?";

        if ($sC = $con->prepare($resC)) {

          $sC->bind_param("i",$i);

          $sC->execute();

          $sC->store_result();

          $resetCode = substr(md5(uniqid(rand(), true)), 16, 16);
          $time = time();

          if ($sC->num_rows == 0) {

            $sC->close();

            $insQ = "INSERT INTO resetRequests (userId, resetCode, time) VALUES (?, ?, ?)";

            if ($ins = $con->prepare($insQ)) {

              $ins->bind_param("isi",$i,$resetCode,$time);

              if ($ins->execute()) {

                $ins->close();

                $m = new PHPMailer;

                $m->setFrom('noreply@collectmemes.com', 'CollectMemes');

                $m->addReplyTo('support@collectmemes.com', 'CollectMemes');

                $m->addAddress($e);

                $m->Subject = 'CollectMemes Password Reset Request';

                $m->msgHTML("Thank you for submitting a password reset request. <a href='https://collectmemes.com/reset?v=" . $resetCode . "'>Set a new password by clicking here.</a> This reset request will be valid for 24 hours.");

                $m->send();

              }

            }

          } else {

            $sC->close();

            $uQ = "UPDATE resetRequests SET resetCode=?,time=? WHERE userId=?";

            if ($uS = $con->prepare($uQ)) {

              $uS->bind_param("sii",$resetCode,$time,$i);

              if ($uS->execute()) {

                $uS->close();

                $m = new PHPMailer;

                $m->setFrom('noreply@collectmemes.com', 'CollectMemes');

                $m->addReplyTo('support@collectmemes.com', 'CollectMemes');

                $m->addAddress($e);

                $m->Subject = 'CollectMemes Password Reset Request';

                $m->msgHTML("Thank you for submitting a password reset request. <a href='https://collectmemes.com/reset?v=" . $resetCode . "'>Set a new password by clicking here.</a> This reset request will be valid for 24 hours.");

                $m->send();

              } else {

                $uS->close();

              }

            }

          }

        }

      } else {

        $s->close();

      }

    }

  }

}

$con->close();
?>
