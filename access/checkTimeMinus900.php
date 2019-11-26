<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$response['time'] = time() - 900;

echo json_encode($response);

$con->close();
?>
