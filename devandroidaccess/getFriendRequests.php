<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['scheme'])) {

  // CLean vars.
  $userId = $con->real_escape_string($_POST['userId']);
  $scheme = $_POST['scheme'];

  // Create array for storing active requests.
  $users = array();

  $nav = array();

  $nav['profileLeft'] = ($scheme == 'light') ? "file://nav/profile-left-light.png" : "file://nav/profile-left-dark.png";

  $response['nav'] = $nav;

  $search = "SELECT senderId FROM friendRequests WHERE userId=?";

  if ($stmt = $con->prepare($search)) {

    $stmt->bind_param("i",$userId);

    $stmt->execute();

    $stmt->store_result();

    $stmt->bind_result($senderId);

    if ($stmt->num_rows > 0) {

      while ($stmt->fetch()) {

        $user = array();

        $user['id'] = $senderId;

        $users[] = $user;

      }

      $stmt->close();

      $requests = array();

      foreach ($users as $user) {

        // Grab other info to display to user.
        $userSearch = "SELECT id,username,avatar FROM users WHERE id=?";

        if ($u = $con->prepare($userSearch)) {

          $u->bind_param("i",$user['id']);

          $u->execute();

          $u->bind_result($id,$username,$avatar);

          if ($u->fetch()) {

            $request = array();

            $request['id'] = $id;

            $request['avatar'] = $avatar;

            $request['username'] = $username;

            $requests[] = $request;

          }

          $u->close();

        }

      }

      $response['hasRequests'] = 1;
      $response['requests'] = $requests;

    } else {

      $stmt->close();

      $response['hasRequests'] = 0;
      $response['requests'] = array();

    }

  } else {

    $response['success'] = $con->error;

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
