<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['username']) and isset($_GET['password']) and isset($_GET['email'])) {

  // Clean + define variables.
  $user = $con->real_escape_string($_GET['username']);
  $password = crypt($con->real_escape_string($_GET['password']), '$2a$07$5jh843257hquiyo7ghfkgi$');
  $email = $con->real_escape_string($_GET['email']);
  $collection = "";
  $lastRoll = date("Y-m-d H:i:s",time() - 1810);
  $curAttempts = 10;

  // Email the user with a welcome email.

  // Insert user into database.
  $query = "INSERT INTO users (username,password,email,collection,lastRoll,curAttempts) VALUES (?, ?, ?, ?, ?, ?)";

  if ($stmt = $con->prepare($query)) {

    $rc = $stmt->bind_param("sssssd", $user, $password, $email, $collection, $lastRoll, $curAttempts);

    $rc = $stmt->execute();

    if ($stmt == false or $rc == false) {

      die(htmlspecialchars($con->error));

    }

    $response['success'] = 1;

    $stmt->close();

  }

} else {

  $response['success'] = 0;
}

echo json_encode($response);

$con->close();

?>
