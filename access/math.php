<?php

$response = array();

if (isset($_POST['x']) and isset($_POST['y'])) {

  if (isset($_POST['add'])) {

    $response['ret'] = (int)$_POST['x'] + (int)$_POST['y'];

  } else if (isset($_POST['sub'])) {

    $response['ret'] = (int)$_POST['x'] - (int)$_POST['y'];

  } else if (isset($_POST['div'])) {

    $response['ret'] = intval((int)$_POST['x'] / (int)$_POST['y']) + 15;

  }

}

echo json_encode($response);

?>
