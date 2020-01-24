<?php

$response = array();

if (isset($_POST['width'])) {

  $response['ret'] = intval(intval($_POST['width']) / 3);
  $response['width'] = intval($_POST['width']);

}

echo json_encode($response);

?>
