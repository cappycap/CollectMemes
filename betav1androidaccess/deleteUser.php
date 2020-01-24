<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['pw'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $p = crypt($con->real_escape_string($_POST['pw']), '$2a$07$5jh843257hquiyo7ghfkgi$');

  $c = "SELECT password FROM users WHERE id=?";

  if ($cs = $con->prepare($c)) {

    $cs->bind_param("i",$u);

    $cs->execute();

    $cs->bind_result($i);

    if ($cs->fetch()) {

      if ($i == $p) {

        $cs->close();

        $q = "DELETE FROM users WHERE id=" . $u;

        if ($con->query($q) === TRUE) {

          $response['success'] = 1;

        }

        $q2 = "DELETE FROM achievementsProgress WHERE userId=" . $u;

        if ($con->query($q2) === TRUE) {

          $response['aSuccess'] = 1;

        }

        $q3 = "DELETE FROM collectionsProgress WHERE userId=" . $u;

        if ($con->query($q3) === TRUE) {

          $response['cSuccess'] = 1;

        }

      } else {

        $cs->close();

        $response['success'] = 0;

      }

    } else {

      $response['success'] = -1;

    }

  }

}

echo json_encode($response);

$con->close();
?>
