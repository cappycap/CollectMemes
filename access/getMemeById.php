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

          $stmt->close();

          $info = getRankInfo($rank, $con);

          // Populate meme array.
          $meme["title"] = $title;
          $meme["image"] = $image;
          $meme["totalOwned"] = number_format($totalOwned);
          $meme['likes'] = number_format($likes);
          $meme["creator"] = $creator;
          $meme['rank'] = $rank;

          if (isset($_POST['liked'])) {
            $meme["rank"] = "<html><body style='font-size:18px;background:#111111;color:#fff;text-align:center;'>Rank: #" . $rank . " <span style='color:" . $info['color'] . ";'>(" . $info['rarity'] . ")</span><br><span style='font-size:15px;'>Not owned!</span></body></html>";
          } else {
            $meme["rank"] = "<html><body style='font-size:22px;background:#111111;color:#fff;text-align:center;'>Rank: #" . $rank . " <span style='color:" . $info['color'] . ";'>(" . $info['rarity'] . ")</span></body></html>";
          }



        } else {

          // That meme wasn't found.
          $response["success"] = 0;
          $response["message"] = "E-00040";
        }


      }

      $q = "SELECT dateAdded FROM owns WHERE userId=? AND memeId=?";

      if ($s = $con->prepare($q)) {

        $s->bind_param("ii",$u,$id);

        $s->execute();

        $s->bind_result($date);

        if ($s->fetch()) {

          $meme['dateCollected'] = date("d M, Y", $date);

        }

        $s->close();

      }

      $response["meme"] = $meme;

    } else {

      // Did not supply ID.
      $response["success"] = 0;
      $response[" message"] = "E-0003";

    }

echo json_encode($response);

$con->close();
?>
