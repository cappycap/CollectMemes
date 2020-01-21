<?php

require 'db.php';

// Define response array for delivering status.
$jason = array();

if (isset($_POST['userId']) and isset($_POST['iconId'])) {

  $i = $con->real_escape_string($_POST['iconId']);

  $iString = "https://collectmemes.com/icons/" . $i . ".jpg";

  $u = $con->real_escape_string($_POST['userId']);

  $q = "UPDATE users SET avatar=? WHERE id=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("si",$iString,$u);

    if ($s->execute()) {

      $jason['success'] = 1;

    } else {

      $jason['success'] = $con->error;

    }

    $s->close();

  }

}

echo json_encode($jason);

$con->close();
?>
