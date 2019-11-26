<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId'])) {

  $userId = $con->real_escape_string($_GET['userId']);

  $queryUserCollection = "SELECT collection,collectionSize FROM users WHERE id=?";

  if ($stmtUser = $con->prepare($queryUserCollection)) {

    $stmtUser->bind_param("i",$userId);

    $stmtUser->execute();

    $stmtUser->bind_result($collection,$collectionSize);

    if ($stmtUser->fetch()) {

      $response['success'] = 1;
      $response['collection'] = $collection;
      $response['collectionSize'] = $collectionSize;

    } else {

      $response['success'] = 0;

    }

    $stmtUser->free_result();

    $stmtUser->close();
    
  }
}
echo json_encode($response);

$con->close();
?>
