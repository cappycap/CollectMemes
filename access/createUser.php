<?php

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

    $stmt->bind_param("sssi", $user, $password, $email, $time, $time);

    $stmt->execute();

    $response['success'] = 1;

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

} else {

  $response['success'] = 0;

}


echo json_encode($response);

$con->close();

?>
