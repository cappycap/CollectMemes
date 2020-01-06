<?php

require 'db.php';

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

      // Compare the passwords.
      if (strcmp($realPwd, $pwd) !== 0) {

        // The password didn't match.
        $response['success'] = 4;

      } else {

        // The password matched.
        $response['success'] = 1;
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
