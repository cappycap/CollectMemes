<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['pw'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $p = crypt($con->real_escape_string($_POST['pw']), '$2a$07$5jh843257hquiyo7ghfkgi$');

  $c = "SELECT id FROM users WHERE password=?";

  if ($cs = $con->prepare($c)) {

    $cs->bind_param("s",$p);

    $cs->execute();

    $cs->bind_result($i);

    if ($cs->fetch()) {

      if ($i == $u) {

        $cs->close();

        $q = "DELETE FROM users WHERE id=" . $u;

        if ($con->query($q) === TRUE) {

          $response['success'] = 1;

        }

      } else {

        $response['success'] = 0;

      }

    } else {

      $response['success'] = 0;
      
    }

  }

}
echo json_encode($response);

$con->close();
?>
