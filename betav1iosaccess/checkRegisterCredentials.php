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
  $eFilterFailed = 0;
  $uLengthFailed = 0;

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

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $eFilterFailed = 1;

  }

  if (strlen($p) < 8 or !preg_match('/[A-Z]/', $p)) {

    $pFailed = 1;

  }

  if (strlen($username) < 3 or strlen($username) > 12) {

    $uLengthFailed = 1;

  }

  // Build response.
  if ($uFailed and $eFailed) {

    $response['success'] = 0;
    $response['failed'] = "That username and email are already taken. Try something else!";

  } else if ($uFailed) {

    $response['success'] = 0;
    $response['failed'] = "That username is already taken. Try something else!";

  } else if ($eFailed) {

    $response['success'] = 0;
    $response['failed'] = "That email is already taken. Try something else!";

  } else {

    $response['success'] = 1;

  }

  if ($response['success'] == 0 and $pFailed) {

    $response['failed'] .= " Also, be sure your password is at least 8 chars long and contains an uppercase letter.";

  } else if ($pFailed) {

    $response['success'] = 0;
    $response['failed'] = "Be sure your password is at least 8 chars long and contains an uppercase letter!";

  }

  if ($eFilterFailed and $response['success'] == 1) {

    $response['success'] = 0;
    $response['failed'] = "Be sure to supply a valid email!";

  } else if ($eFilterFailed) {

    $response['failed'] .= " And be sure to supply a valid email.";

  }

  if ($uLengthFailed and $response['success'] == 1) {

    $response['success'] = 0;
    $response['failed'] = "Username needs to be between 3 and 12 chars.";

  } else if ($uLengthFailed) {

    $response['failed'] .= " Username needs to be between 3 and 12 chars.";

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
