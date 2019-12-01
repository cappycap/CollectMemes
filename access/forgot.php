<?php

require 'db.php';

if (isset($_POST['email'])) {

  $email = $con->real_escape_string($_POST['email']);

  $q = "SELECT id FROM users WHERE email=?";

  if ($stmt = $con->prepare($q)) {

    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->bind_result($id);

    if ($stmt->fetch()) {

      $uId = $id;

      $stmt->close();

      $receipt = md5(uniqid(rand(), true));
      $time = time();

      $inQ = "INSERT INTO recover (userId, receipt, stamptime) VALUES (?, ?, ?)";

      if ($iStmt = $con->prepare($inQ)) {

        $rc = $iStmt->bind_param("iss",$uId,$receipt,$time);

        $rc = $iStmt->execute();

        if ($iStmt == false or $rc == false) {

          die (htmlspecialchars($con->error));

        }

        $iStmt->close();
      }

    }

  }

}

$con->close();
?>
