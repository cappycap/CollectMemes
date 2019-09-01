<?php

require 'db.php';

// Define meme and response array.
$meme = array();
$response = array();

// Start by checking they got the access key right.
if (isset($_GET['key'])) {

  if ($_GET['key'] == "askkilfefuy8or62463ufsdmnkbflu4iy532457896terugh") {

    // Password is correct. Now, let's check if they supplied an ID.
    if (isset($_GET['id'])) {

      // Clean ID.
      $id = $con->real_escape_string($_GET['id']);

      // Prepare and execute query.
      $query = "SELECT * FROM memes WHERE id=?";
      if ($stmt = $con->prepare($query)) {

        // Bind ID to parameter in query.
        $stmt->bind_param("i",$id);
        $stmt->execute();

        // Bind fetched results to variables.
        $stmt->bind_result($id,$title,$image,$totalOwned,$rank,$inRotation,$edition,$source,$creator,$dateAdded);

        // Check for results.
        if ($stmt->fetch()) {

          // Populate meme array.
          $meme["id"] = $id;
          $meme["title"] = $title; //
          $meme["image"] = $image; //
          $meme["totalOwned"] = $totalOwned;
          $meme["rank"] = $rank;
          $meme["inRotation"] = $inRotation;
          $meme["edition"] = $edition; //
          $meme["source"] = $source; //
          $meme["creator"] = $creator; //
          $meme["dateAdded"] = $dateAdded;
          $response["success"] = 1;
          $response["message"] = $meme;

        } else {

          // That meme wasn't found.
          $response["success"] = 0;
          $response["message"] = "E-00040";
        }

      }


    } else {

      // Did not supply ID.
      $response["success"] = 1;
      $response[" message"] = "E-0003";

    }




  } else {

    // Password is wrong.
    $response["success"] = 0;
    $response["message"] = "E-0002";

  }

} else {

  // Did not supply password.
  $response["success"] = 0;
  $response["message"] = "E-0001";
}

echo json_encode($response);

$con->close();
?>
