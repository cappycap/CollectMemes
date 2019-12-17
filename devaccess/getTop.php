<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['type']) and isset($_GET['userId'])) {

  $type = $con->real_escape_string($_GET['type']);
  $userId = $con->real_escape_string($_GET['userId']);

  if ($type == 1) {

    $query = "SELECT id,username,avatar,avgRank FROM users WHERE friends LIKE '%{$userId}%' ORDER BY avgRank DESC";

    if ($stmt = $con->prepare($query)) {

      $stmt->execute();

      $result = $stmt->get_result();

      $stmt->close();

      if ($result->num_rows > 0) {

        $users = array();

        while ($data = $result->fetch_assoc()) {

          $profile = array();

          $profile['username'] = $data['username'];

          $profile['avatar'] = $data['avatar'];

          $profile['avgRank'] = $data['avgRank'];

          $users[$data['id']] = $profile;

        }

        $response['success'] = 1;
        $response['users'] = $users;

      } else {

        $response['users'] = 0;

      }

    }

  } else if ($type == 0) {

    $query = "SELECT id,username,avatar,avgRank FROM users ORDER BY avgRank DESC LIMIT 10";

    if ($stmt = $con->prepare($query)) {

      $stmt->execute();

      $result = $stmt->get_result();

      $stmt->close();

      if ($result->num_rows > 0) {

        $users = array();

        while ($data = $result->fetch_assoc()) {

          $profile = array();

          $profile['username'] = $data['username'];

          $profile['avatar'] = $data['avatar'];

          $profile['avgRank'] = $data['avgRank'];

          $users[$data['id']] = $profile;

        }

        $response['success'] = 1;
        $response['users'] = $users;

      } else {

        $response['users'] = 0;

      }

    }

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
