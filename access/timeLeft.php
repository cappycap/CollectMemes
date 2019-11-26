<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['lastCollect'])) {

  $lc = (int) $_POST['lastCollect'];

  $response['timeLeft'] = 900 - (time() - $lc);

}


echo json_encode($response);

$con->close();
?>
