<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) && isset($_POST['newFriendUserId'])) {
  
}
echo json_encode($response);

$con->close();
?>
