<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['oldEmail']) and isset($_POST['newEmail']) and isset($_POST['password'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $o = $con->real_escape_string($_POST['oldEmail']);
  $n = $con->real_escape_string($_POST['newEmail']);
  $p = crypt($con->real_escape_string($_POST['password']), '$2a$07$5jh843257hquiyo7ghfkgi$');

  $cQ = "SELECT password FROM users WHERE id=?";

  if ($cQS = $con->prepare($cQ)) {

    $cQS->bind_param("i",$u);

    $cQS->execute();

    $cQS->bind_result($pw);

    if ($cQS->fetch()) {

      $cQS->close();

      if ($p == $pw) {

        if (filter_var($n, FILTER_VALIDATE_EMAIL)) {

          $eQ = "SELECT id FROM users WHERE email=?";

          if ($eQS = $con->prepare($eQ)) {

            $eQS->bind_param("s",$n);

            $eQS->execute();

            $eQS->store_result();

            if ($eQS->num_rows == 0) {

              $eQS->close();

              $q = "UPDATE users SET email=? WHERE id=?";

              if ($s = $con->prepare($q)) {

                $s->bind_param("si",$n,$u);

                $s->execute();

                $s->close();

                $old = new PHPMailer;

                $old->setFrom('noreply@collectmemes.com', 'CollectMemes');

                $old->addReplyTo('support@collectmemes.com', 'CollectMemes');

                $old->addAddress($o);

                $old->Subject = 'CollectMemes Email Changed';

                $old->msgHTML("Your CollectMemes account's primary email address has been updated to " . $n . ". If this wasn't you, please contact support@collectmemes.com right away so we can remedy the situation.");

                $old->send();

                $new = new PHPMailer;

                $new->setFrom('noreply@collectmemes.com', 'CollectMemes');

                $new->addReplyTo('support@collectmemes.com', 'CollectMemes');

                $new->addAddress($n);

                $new->Subject = 'CollectMemes Email Changed';

                $new->msgHTML("Congrats! Your new email has been associated with your CollectMemes account.");

                $new->send();

                if ($old->send() and $new->send()) {

                  $response['success'] = 1;

                } else {

                  $response['success'] = 2;

                }

              }

            } else {

              $eQS->close();

              $response['success'] = 0;
              $response['message'] = "That email is already taken. Try something else!";

            }

          }

        } else {

          $response['success'] = 0;
          $response['message'] = "That wasn't a valid email. Try something like example@domain.com!";

        }

      } else {

        $response['success'] = 0;
        $response['message'] = "Your password was incorrect. Try again!";

      }

    } else {

      $cQS->close();

    }

  }

}
echo json_encode($response);

$con->close();
?>
