<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$response['time'] = time() - 901;

echo json_encode($response);

$con->close();
?>
