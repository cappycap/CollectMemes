<?php

require 'db.php';

// Define response array for delivering status.
$challenges = array();

if (isset($POST['userId']) and isset($_POST[''])) {

  $u = $con->real_escape_string($_POST['userId']);

  $cpQ = "SELECT collectionId,totalOwned,completed FROM collectionsProgress WHERE userId=?";

  if ($cpS = $con->prepare($cpQ)) {

    $cpS->bind_param("i",$u);

    $cpS->execute():

    $cpS->bind_result($collectionId,$totalOwned,$completed);

    while ($cpS->fetch()) {

      $chal = array();

      $chal['id'] = ;
      $chal[''] = ;
      $chal[''] = ;

    }

    $cpS->close();

  }

}
echo json_encode($challenges);

$con->close();
?>
