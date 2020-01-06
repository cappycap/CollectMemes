<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId']) and isset($_POST['rank'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);
  $r = (int)$con->real_escape_string($_POST['rank']);

  // Remove from owns table.
  $deleteQ = "DELETE FROM owns WHERE userId=" . $userId . " AND memeId=" . $memeId;

  if ($con->query($deleteQ) === TRUE) {

    $response['delSucc'] = 1;

  } else {

    $response['delSucc'] = 0;

  }

  $selectQuery = "SELECT collectionSize,collectionSum FROM users WHERE id=?";

  if ($selectStmt = $con->prepare($selectQuery)) {

    $selectStmt->bind_param("i",$userId);

    $selectStmt->execute();

    $selectStmt->bind_result($collectionSize,$collectionSum);

    if ($selectStmt->fetch()) {

      if ($collectionSize != 1) {

        $newCollectionSum = $collectionSum - $r;
        $newAvgRank = ($newCollectionSum) / ($collectionSize - 1);

      } else {

        $newCollectionSum = 0;
        $newAvgRank = 0;
        
      }

      $selectStmt->close();

      $updateQuery = "UPDATE users SET avgRank=?collectionSum=?,collectionSize=collectionSize-1 WHERE id=?";

      if ($updateStmt = $con->prepare($updateQuery)) {

        $updateStmt->bind_param("iii",$newAvgRank,$newCollectionSum,$userId);

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
