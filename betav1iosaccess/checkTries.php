<?php

$response = array();

if (isset($_POST['tries'])) {

  if ($_POST['tries'] == "undefined") {

    $response['success'] = 1;

  } else {

    $t = (int) $_POST['tries'];

    if ($t > 0) {

      $response['success'] = 0;

    } else {

      $response['success'] = 2;

    }

  }

}

echo json_encode($response);

?>
