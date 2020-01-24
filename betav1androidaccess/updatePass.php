<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['n']) and isset($_POST['userId']) and isset($_POST['newPass']) and isset($_POST['newPassConfirm']) and isset($_POST['currentPass'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $np = crypt($con->real_escape_string($_POST['newPass']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $npc = crypt($con->real_escape_string($_POST['newPassConfirm']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $cp = crypt($con->real_escape_string($_POST['currentPass']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $n = $con->real_escape_string($_POST['n']);

  if ($np == $npc) {

    if (strlen($np) < 8 or !preg_match('/[A-Z]/', $np)) {

      $response['success'] = 0;
      $response['message'] = "New password must be at least 8 characters and contain an uppercase letter. Try again!";

    } else {

      $c = "SELECT password FROM users WHERE id=?";

      if ($cs = $con->prepare($c)) {

        $cs->bind_param("i",$u);

        $cs->execute();

        $cs->bind_result($pw);

        if ($cs->fetch()) {

          if ($cp == $pw) {

            $cs->close();

            $q = "UPDATE users SET password=?,lastPassChange=? WHERE id=?";

            if ($qs = $con->prepare($q)) {

              $time=time();

              $qs->bind_param("sii",$np,$time,$u);

              $qs->execute();

              if ($qs->execute()) {

                $new = new PHPMailer;

                $new->setFrom('noreply@collectmemes.com', 'CollectMemes');

                $new->addReplyTo('support@collectmemes.com', 'CollectMemes');

                $new->addAddress($n);

                $new->Subject = 'CollectMemes Password Changed';

                $new->msgHTML("Your CollectMemes account password has been updated. If this was not you, please contact support@collectmemes.com immediately.");

                $new->send();

                $response['success'] = 1;

              } else {

                $response['success'] = 0;
                $response['message'] = "Problem connecting to database. Try again!";

              }

              $qs->close();

            }

          } else {

            $cs->close();

            $response['success'] = 0;
            $response['message'] = "Your current password was incorrect. Give it another shot!";

          }

        }

      }

    }

  } else {

    $response['success'] = 0;
    $response['message'] = "New passwords need to match. Try again!";

  }

}
echo json_encode($response);

$con->close();
?>
