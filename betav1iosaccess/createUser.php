<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'db.php';
function createAchievementsProgress($uid, $con) {

  $ret = 0;

  $progress = "0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";

  $q = "INSERT INTO achievementsProgress (userId, progress) VALUES (?, ?)";

  if ($s = $con->prepare($q)) {

    $s->bind_param("is",$uid,$progress);

    if ($s->execute()) {

      $ret = 1;

    }

    $s->close();

  }

  return $ret;

}

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

        $count++;

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
function updateAchievementsProgress($userId, $achievementId, $stage, $con) {

  $ret = false;

  $q = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($progress);

    if ($s->fetch()) {

			$s->close();

	    $p = explode(",",$progress);

	    $p[$achievementId] = $stage;

	    $new = implode(",",$p);

	    $uQ = "UPDATE achievementsProgress SET progress=? WHERE userId=?";

	    if ($u = $con->prepare($uQ)) {

	      $u->bind_param("si",$new,$userId);

	      if ($u->execute()) {

	        $ret = true;

	      }

	      $u->close();

	    }

		}

  }

  return $ret;

}

function checkAchievements($userId, $device, $con) {

  $achievement = array();

  $q = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($progressStr);

    if ($s->fetch()) {

      $prog = explode(",",$progressStr);

      $s->close();

      if ($device == 'ios') {

        $check = ($prog[6] == 1) ? 0 : 1;

        if ($check) {

          $achievement['status'] = 1;

          $update = updateAchievementsProgress($userId, 6, 1, $con);

          $xp = giveXP($userId, 100, $con);

        } else {

          $achievement['status'] = 0;

        }

      } elseif ($device == 'android') {

        $check = ($prog[7] == 1) ? 0 : 1;

        if ($check) {

          $achievement['status'] = 1;

          $update = updateAchievementsProgress($userId, 7, 1, $con);

          $xp = giveXP($userId, 100, $con);

        } else {

          $achievement['status'] = 0;

        }

      } else {

        $achievement['status'] = 0;

      }

    }

  }


  return $achievement;

}

// Define response array for delivering status.
$response = array();

if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['email']) and isset($_POST['device'])) {

  // Clean + define variables.
  $user = $con->real_escape_string($_POST['username']);
  $password = crypt($con->real_escape_string($_POST['password']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $email = $con->real_escape_string($_POST['email']);
  $time = time();
  $device = $con->real_escape_string($_POST['device']);

  // Email the user with a welcome email.

  // Insert user into database.
  $query = "INSERT INTO users (username,password,email,dateRegistered,lastPassChange,nextSpin,spinsLeft,lastDevice,lastLogin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

  if ($stmt = $con->prepare($query)) {

    $lastNextSpin = $time - 3610;
    $lastSpinsLeft = 11;

    $stmt->bind_param("sssiiiisi", $user, $password, $email, $time, $time, $lastNextSpin, $lastSpinsLeft, $device, $time);

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

  createAchievementsProgress($response['userKey'], $con);

  $response['achievement'] = checkAchievements($response['userKey'], $device, $con);

} else {

  $response['success'] = 0;

}


echo json_encode($response);

$con->close();

?>
