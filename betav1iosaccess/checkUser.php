<?php

require 'db.php';
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

if (isset($_POST['password']) and isset($_POST['username']) and isset($_POST['device'])) {

  // Clean variables.
  $pwd = crypt($con->real_escape_string($_POST['password']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $user = $con->real_escape_string($_POST['username']);
  $device = $con->real_escape_string($_POST['device']);
  $time = time();

  $response['pwd'] = $pwd;

  // Prepare and execute query.
  $query = "SELECT id,password,nextSpin,spinsLeft FROM users WHERE username=?";

  if ($stmt = $con->prepare($query)) {

    // Bind username parameter to query.
    $stmt->bind_param("s",$user);
    $stmt->execute();

    // Bind fetched results to variables.
    $stmt->bind_result($id,$realPwd,$nextSpin,$spinsLeft);

    // Check for results.
    if ($stmt->fetch()) {

      $stmt->close();

      // Compare the passwords.
      if (strcmp($realPwd, $pwd) !== 0) {

        // The password didn't match.
        $response['success'] = 4;

      } else {

        // The password matched.
        $response['success'] = 1;
        $response['achievement'] = checkAchievements($id, $device, $con);
        $response['userKey'] = $id;
        $response['spinsLeft'] = $spinsLeft;
        $response['nextSpin'] = $nextSpin;

        $q = "UPDATE users SET lastDevice=?, lastLogin=? WHERE id=?";

        if ($s = $con->prepare($q)) {

          $s->bind_param("sii",$device,$time,$id);

          if ($s->execute()) {

            $response['uUser'] = 1;

          }

          $s->close();

        }

      }

    } else {

      $response['success'] = 0;

    }

  } else {

    $response['success'] = 2;

    echo $con->error;

  }

} else {

  $response['success'] = 3;

}

echo json_encode($response, JSON_UNESCAPED_SLASHES);

$con->close();
?>
