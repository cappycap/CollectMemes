<?php

require 'db.php';

// Define meme and response array.
$meme = array();
$response = array();

    if (isset($_POST['id']) and isset($_POST['userKey'])) {

      // Clean ID.
      $id = $con->real_escape_string($_POST['id']);
      $u = $con->real_escape_string($_POST['userKey']);

      // Prepare and execute query.
      $query = "SELECT title,image,totalOwned,likes,rank,source,creator FROM memes WHERE id=?";
      if ($stmt = $con->prepare($query)) {

        // Bind ID to parameter in query.
        $stmt->bind_param("i",$id);
        $stmt->execute();

        // Bind fetched results to variables.
        $stmt->bind_result($title,$image,$totalOwned,$likes,$rank,$source,$creator);

        // Check for results.
        if ($stmt->fetch()) {

          // Populate meme array.
          $meme["title"] = $title;
          $meme["image"] = $image;
          $meme["totalOwned"] = $totalOwned;
          $meme['likes'] = $likes;
          $meme["rank"] = $rank;
          $meme["source"] = $source;
          $meme["creator"] = $creator;

          $response["meme"] = $meme;

        } else {

          // That meme wasn't found.
          $response["success"] = 0;
          $response["message"] = "E-00040";
        }

        $stmt->close();

      }

      $q = "SELECT dateAdded FROM owns WHERE userId=? AND memeId=?";

      if ($s = $con->prepare($q)) {

        $s->bind_param("ii",$u,$id);

        $s->execute();

        $s->bind_result($date);

        if ($s->fetch()) {

          $response['dateCollected'] = date("d M, Y", $date);

        }

        $s->close();
        
      }


    } else {

      // Did not supply ID.
      $response["success"] = 0;
      $response[" message"] = "E-0003";

    }

echo json_encode($response);

$con->close();
?>
