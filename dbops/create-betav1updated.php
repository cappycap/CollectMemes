<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$users = array();

$q = "SELECT id FROM users WHERE 1";

if ($s = $con->prepare($q)) {

  $s->execute();

  $s->bind_result($userId);

  while ($s->fetch()) {

    $user = array();

    $user['id'] = $userId;

    $users[] = $user;

  }

  $s->close();

}

foreach ($users as $user) {

  $iQ = "INSERT INTO betav1updated (userId) VALUES (?)";

  if ($i = $con->prepare($iQ)) {

    $i->bind_param("i",$user['id']);

    if ($i->execute()) {

      $response['success'] = 1;

    } else {

      $response['success'] = $i->error;

    }

  }
  
}
echo json_encode($response);

$con->close();
?>
