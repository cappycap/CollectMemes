<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['key'])) {

  if ($_GET['key'] == "askkilfefuy8or62463ufsdmnkbflu4iy532457896terugh") {

    $query = "SELECT id FROM memes ORDER BY id";

    if ($result = $con->query($query)) {

      $response['success'] = 1;
      $response['dbSize'] = $result->num_rows;

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
