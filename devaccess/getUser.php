<?php

require 'db.php';

// Define response array for delivering status.
$response = array();
$profile = array();

if (isset($_GET['key'])) {

  if ($_GET['key'] == "askkilfefuy8or62463ufsdmnkbflu4iy532457896terugh") {

    if (isset($_GET['username'])) {

      // Clean username.
      $username = $con->real_escape_string($_GET['username']);

      // Prepare and execute query.
      $query = "SELECT username,collection,friends,avgRank,displayInFriendsList FROM users WHERE username=?";
      if ($stmt = $con->prepare($query)) {

        // Bind ID to parameter in query.
        $stmt->bind_param("s",$username);
        $stmt->execute();

        $stmt->bind_result($username,$collection,$friends,$avgRank,$displayInFriendsList);

        if ($stmt->fetch()) {

          $profile['username'] = $username;
          $profile['collection'] = $collection;
          $profile['avgRank'] = $avgRank;
          $profile['friends'] = $friends;
          $profile['displayInFriendsList'] = $displayInFriendsList;
          $response['success'] = 1;
          $response['profile'] = $profile;

        } else {

          $response["success"] = 0;

        }

      } else {

        $response["success"] = 0;

      }

    } else {

      $response["success"] = 0;

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
