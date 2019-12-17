<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function createCollectionsProgress($uid, $con) {

  $check = array();

  // Call to collections
  $collectionsQ = "SELECT id,memes FROM collections WHERE 1";

  $collections = array();

  if ($a = $con->prepare($collectionsQ)) {

    $a->execute();

    $a->bind_result($cId,$cMemes);

    while ($a->fetch()) {

      $length = count(explode(",",$cMemes));

      $collections[] = array("id"=>$cId,"memes"=>$length);

    }

    $a->close();

    // Create progress trackers for users
    foreach ($collections as $col) {

      $memes = "0";
      $l = $col['memes'] - 1;

      $count = 0;

      while ($count < $l) {

        $memes .= ",0";

      }

      $ins = "INSERT INTO collectionsProgress (userId,collectionId,memes,completed) VALUES (?, ?, ?, ?)";

      if ($q = $con->prepare($ins)) {

        $completed = 0;
        
        $q->bind_param("iisi",$uid,$col['id'],$memes,$completed);

        if ($q->execute()) {

          $check[] = 1;

        } else {

          $check[] = 0;

        }

        $q->close();

      }

    }

  }

  return !in_array(0, $check);

}

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['email'])) {

  // Clean + define variables.
  $user = $con->real_escape_string($_POST['username']);
  $password = crypt($con->real_escape_string($_POST['password']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $email = $con->real_escape_string($_POST['email']);
  $time = time();

  // Email the user with a welcome email.

  // Insert user into database.
  $query = "INSERT INTO users (username,password,email,dateRegistered,lastPassChange) VALUES (?, ?, ?, ?,?)";

  if ($stmt = $con->prepare($query)) {

    $stmt->bind_param("sssii", $user, $password, $email, $time, $time);

    $stmt->execute();

    $response['success'] = 1;

    $new = new PHPMailer;

    $new->setFrom('noreply@collectmemes.com', 'CollectMemes');

    $new->addReplyTo('support@collectmemes.com', 'CollectMemes');

    $new->addAddress($email);

    $new->Subject = 'Welcome to CollectMemes!';

    $new->msgHTML("Thanks for signing up with us, we hope you enjoy collecting memes! <br>Please note: CollectMemes is currently in alpha. Any feedback would be greatly appreciated, just send an email to feedback@collectmemes.com!");

    $new->send();

    $stmt->close();

  }

  // Return userKey.
  $q = "SELECT id FROM users WHERE username=?";

  if ($stmt = $con->prepare($q)) {

    $stmt->bind_param("s",$user);
    $stmt->execute();
    $stmt->bind_result($id);

    if ($stmt->fetch()) {

      $response['userKey'] = $id;

    }

    $stmt->close();

  }

  createCollectionsProgress($response['userKey'], $con);

} else {

  $response['success'] = 0;

}


echo json_encode($response);

$con->close();

?>
