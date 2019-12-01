<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['username']) and isset($_POST['email']) and isset($_POST['password'])) {

  $username = $con->real_escape_string($_POST['username']);
  $email = $con->real_escape_string($_POST['email']);
  $p = $con->real_escape_string($_POST['password']);

  $uFailed = 0;
  $eFailed = 0;
  $pFailed = 0;

  // Check username.
  $uQ = "SELECT id FROM users WHERE username=?";

  if ($stmt = $con->prepare($uQ)) {

    $stmt->bind_param("s",$username);
    $stmt->execute();

    $stmt->bind_result($id);

    if ($stmt->fetch()) {

      $uFailed = 1;

    }

    $stmt->close();

  }

  // Check email.
  $eQ = "SELECT id FROM users WHERE email=?";

  if ($stmt = $con->prepare($eQ)) {

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $stmt->bind_result($id);

    if ($stmt->fetch()) {

      $eFailed = 1;

    }

    $stmt->close();

  }

  // Check p.
  if (strlen($p) < 8 or !preg_match('/[A-Z]/', $p)) {

    $pFailed = 1;

  }

  // Build response.
  if ($uFailed and $eFailed) {

    $response['success'] = 0;
    $response['failed'] = "That username and email were already taken. Try something else!";

  } else if ($uFailed) {

    $response['success'] = 0;
    $response['failed'] = "That username was already taken. Try something else!";

  } else if ($eFailed) {

    $response['success'] = 0;
    $response['failed'] = "That email was already taken. Try something else!";

  } else {

    $response['success'] = 1;

  }

  if ($response['success'] == 0 and $pFailed) {

    $response['failed'] .= " Also, be sure your password is at least 8 chars long and contains an uppercase letter."

  } else if ($pFailed) {

    $response['success'] = 0;
    $response['failed'] = "Be sure your password is at least 8 chars long and contains an uppercase letter!";

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
