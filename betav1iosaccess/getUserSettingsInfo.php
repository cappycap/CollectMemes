<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId'])) {

  $userId = $con->real_escape_string($_POST['userId']);

  $q = "SELECT username,email,avatar,dateRegistered,totalSpins,lastPassChange FROM users WHERE id=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($username,$email,$avatar,$date,$totalSpins,$lastPassChange);

    if ($s->fetch()) {

      $response['username'] = $username;

      $response['email'] = $email;

      $response['avatar'] = $avatar;

      $response['date'] = date("d M, Y", $date);

      $response['totalSpins'] = number_format($totalSpins);

      $response['lastPassChange'] = date("g:i A, d M, Y", $lastPassChange);

    }

    $s->close();

  }

}

echo json_encode($response);

$con->close();
?>
