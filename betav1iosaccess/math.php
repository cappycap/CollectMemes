<?php

$response = array();

if (isset($_POST['x']) and isset($_POST['y'])) {

  if (isset($_POST['add'])) {

    $response['ret'] = (int)$_POST['x'] + (int)$_POST['y'];

  } else if (isset($_POST['sub'])) {

    $response['ret'] = (int)$_POST['x'] - (int)$_POST['y'];

  } else if (isset($_POST['div'])) {

    $response['ret'] = intval(((float)$_POST['x'] / (float)$_POST['y']) - (float)$_POST['z']);

  }

}

echo json_encode($response);

?>
