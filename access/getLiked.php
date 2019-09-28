<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId'])) {

  $userId = $con->real_escape_string($_GET['userId']);

  $queryUserCollection = "SELECT likes,likesSize FROM users WHERE id=?";

  if ($stmtUser = $con->prepare($queryUserCollection)) {

    $stmtUser->bind_param("i",$userId);

    $stmtUser->execute();

    $stmtUser->bind_result($likes,$likesSize);

    if ($stmtUser->fetch()) {

      $response['success'] = 1;
      $response['likes'] = $likes;
      $response['likesSize'] = $likesSize;

    } else {

      $response['success'] = 0;

    }
    
  }
}
echo json_encode($response);

$con->close();
?>
