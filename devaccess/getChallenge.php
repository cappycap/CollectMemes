<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

echo json_encode($response);

$con->close();
?>
