<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$response['time'] = time() - 600;

echo json_encode($response);

$con->close();
?>
