<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId']) and isset($_GET['memeId'])) {

  $userId = $con->real_escape_string($_GET['userId']);
  $memeId = $con->real_escape_string($_GET['memeId']);

  $selectQuery = "SELECT collection,collectionSize FROM users WHERE id=?";

  if ($selectStmt = $con->prepare($selectQuery)) {

    $selectStmt->bind_param("i",$userId);

    $selectStmt->execute();

    $selectStmt->bind_result($collection,$collectionSize);

    if ($selectStmt->fetch()) {

      $newestCollection = "";

      if ($collectionSize != 1) {

        // Attempt to remove as 1,(2),3 -> 1,,,3 or (2),1,3 -> ,,1,3
        $newCollection = str_replace(strval($memeId), ",", $collection);

        // Another replace to handle the broken commas
        // 1,,,3 -> 1,3
        // ,,1,3 -> 1,3
        $newestCollection = str_replace(",,", "", $newCollection);

      }

      $selectStmt->close();

      $updateQuery = "UPDATE users SET collection=?,collectionSize=collectionSize-1 WHERE id=?";

      if ($updateStmt = $con->prepare($updateQuery)) {

        $updateStmt->bind_param("si",$newestCollection,$userId);

        if ($updateStmt->execute()) {

          $response['success'] = 1;

        } else {

          $response['success'] = $updateStmt->error;

        }

        $updateStmt->close();

      }

    }

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
