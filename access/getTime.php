<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$t = time();

if (isset($_GET['removeSec'])) {

  $t = $t - $_GET['removeSec'];

}

$response['date'] = date("Y-m-d H:i:s",$t);

echo json_encode($response);

$con->close();
?>
