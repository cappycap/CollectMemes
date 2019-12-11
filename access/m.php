<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

$q = "SELECT image FROM memes WHERE 1";

if ($s = $con->prepare($q)) {

  $s->execute();

  $s->bind_result($i);

  while ($s->fetch()) {

    echo "<img src='" . $i . "'><br>";

  }

  $s->close();

}

$con->close();
?>
