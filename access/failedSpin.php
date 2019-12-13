<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['nextSpin'])) {

  $timeLeft = (int)$_POST['nextSpin'] - time();

  $minutes = floor(($timeLeft / 60) % 60);
  $seconds = $timeLeft % 60;

  $response['message'] = "Spin again in " . $minutes . "m " . $seconds . "s.";


}

echo json_encode($response);

$con->close();
?>
