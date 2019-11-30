<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$time = time();

if (isset($_POST['sub') {

	$sub = -1 * ((int) $_POST['sub']);
	$time += $sub;

} else if (isset($_POST['add'])) {

	$time += abs($_POST['add'];

}

$response['time'] = $time;

echo json_encode($response);

$con->close();
?>
