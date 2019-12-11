<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['pw'])) {

  if ($_POST['pw'] == "i987hbn2v23hjkg7vgbgk3hbAHRJ198") {

    $u = $con->real_escape_string($_POST['userId']);

    $q = "DELETE FROM users WHERE id=" . $u;

    if ($con->query($q) === TRUE) {

      $response['success'] = 1;

    }

  }

}
echo json_encode($response);

$con->close();
?>
