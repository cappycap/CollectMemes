<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);

  // Remove from owns table.
  $deleteQ = "DELETE FROM likes WHERE userId=" . $userId . " AND memeId=" . $memeId;

  if ($con->query($deleteQ) === TRUE) {

    $response['delSucc'] = 1;

  } else {

    $response['delSucc'] = 0;

  }

  $s = "UPDATE users SET likesSize=likesSize-1 WHERE id=?";

  if ($ss = $con->prepare($s)) {

    $ss->bind_param("i",$userId);

    $ss->execute();

    $ss->close();

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
