<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId']) and isset($_GET['targetId']) and isset($_GET['decision'])) {

  $userId = $con->real_escape_string($_GET['userId']);
  $targetId = $con->real_escape_string($_GET['targetId']);
  $decision = $con->real_escape_string($_GET['decision']);

  if ($decision == 0) {

    $selectQuery = "SELECT blockList,blockListSize FROM users WHERE id=?";

    if ($selectStmt = $con->prepare($selectQuery)) {

      $selectStmt->bind_param("i",$userId);

      $selectStmt->execute();

      $selectStmt->bind_result($list,$size);

      if ($selectStmt->fetch()) {

        $finalList = "";

        if ($size > 1) {

          // Attempt to remove as 1,(2),3 -> 1,,,3 or (2),1,3 -> ,,1,3
          $temp = str_replace(strval($targetId), ",", $list);

          // Another replace to handle the broken commas
          // 1,,,3 -> 1,3
          // ,,1,3 -> 1,3
          $finalList = str_replace(",,", "", $temp);

        }

        $selectStmt->close();

        $updateQuery = "UPDATE users SET blockList=?,blockListSize=blockListSize-1 WHERE id=?";

        if ($updateStmt = $con->prepare($updateQuery)) {

          $updateStmt->bind_param("si",$finalList,$userId);

          if ($updateStmt->execute()) {

            $response['success'] = 1;

          } else {

            $response['success'] = 0;

          }

          $updateStmt->close();

        }

      }

    }

  } else if ($decision == 1) {

    $selectQuery = "SELECT blockList,blockListSize FROM users WHERE id=?";

    if ($selectStmt = $con->prepare($selectQuery)) {

      $selectStmt->bind_param("i",$userId);

      $selectStmt->execute();

      $selectStmt->bind_result($list,$size);

      if ($selectStmt->fetch()) {

        $newList = "";

        if (intval($size) == 0) {

          $newList = strval($targetId);

        } else {

          $newList = strval($list) . "," . strval($targetId);

        }

        $selectStmt->close();

        $updateQuery = "UPDATE users SET blockList=?,blockListSize=blockListSize+1 WHERE id=?";

        if ($updateStmt = $con->prepare($updateQuery)) {

          $updateStmt->bind_param("si",$newList,$userId);

          if ($updateStmt->execute()) {

            $response['success'] = 1;

          } else {

            $response['success'] = 0;

          }

          $updateStmt->close();

        }

      }

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
