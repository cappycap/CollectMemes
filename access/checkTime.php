<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$response['time'] = time();

echo json_encode($response);

$con->close();
?>
