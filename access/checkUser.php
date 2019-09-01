<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['password']) and isset($_GET['username'])) {

  // Clean variables.
  $pwd = $con->real_escape_string($_GET['password']);
  $user = $con->real_escape_string($_GET['username']);

  // Prepare and execute query.
  $query = "SELECT password FROM users WHERE username=?";

  if ($stmt = $con->prepare($query)) {

    // Bind username parameter to query.
    $stmt->bind_param("s",$user);
    $stmt->execute();

    // Bind fetched results to variables.
    $stmt->bind_result($realPwd);

    // Check for results.
    if ($stmt->fetch()) {

      // Compare the passwords.
      if (strcmp($realPwd, $pwd) !== 0) {

        // The password didn't match.
        $response['success'] = 0;

      } else {

        // The password matched.
        $response['success'] = 1;

      }

    } else {

      $response['success'] = 0;

    }
  } else {

    $response['success'] = 0;

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
