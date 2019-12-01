<html>
<head>
  <meta charset="utf-8">
  <title>CollectMemes - Reset Password</title>
  <meta name="description" content="Build a personal collection of the world's most popular memes, and share with your friends!"/>

  <meta name="viewport" content="width=1000, initial-scale=1.0, maximum-scale=1.0">

  <!-- Loading Bootstrap -->
  <link href="dist/css/vendor/bootstrap.min.css" rel="stylesheet">

  <!-- Loading Flat UI -->
  <link href="dist/css/flat-ui.css" rel="stylesheet">
  <link href="docs/assets/css/demo.css" rel="stylesheet">

  <link rel="shortcut icon" href="dist/favicon.ico">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
  <!--[if lt IE 9]>
    <script src="dist/js/vendor/html5shiv.js"></script>
    <script src="dist/js/vendor/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <div class="container">

    <nav class="navbar navbar-inverse navbar-expand-lg" role="navigation" style="margin-top:100px;">
      <a href="http://collectmemes.com"><span class="navbar-brand fui-arrow-left"></span></a><a class="navbar-brand" href="#" style="padding-left:0px;">Submit Meme</a>

    </nav><!-- /navbar -->
    <?php

    require 'access/db.php';

    if (isset($_GET['pr'])) {

      $receipt = $con->real_escape_string($_GET['pr']);

      $q = "SELECT userId,stamptime FROM recover WHERE receipt=?";

      if ($stmt = $con->prepare($q)) {

        $stmt->bind_param("s",$receipt);
        $stmt->execute();
        $stmt->bind_result($id,$time);

        if ($stmt->fetch()) {

          if ((time() - (int)$time) > 28800) {

            echo "<p style='text-align:center;'>This password reset link has expired. For your account's safety, create a new request from within the app.</p>";

          } else {

            $valid = 0;

            if (isset($_POST['submit'])) {

              $errors = array();
              $valid = 1;

              $p = $con->real_escape_string($_POST['password']);
              $pC = $con->real_escape_string($_POST['passwordConfirm']);

              if ($p !== $pC) {

                $valid = 0;
                array_push($errors,"The passwords you entered did not match.");

              }

              if (strlen($p) < 8) {

                $valild = 0;
                array_push($errors,"Your password must be at least 8 characters.");

              }

              if (!preg_match('/[A-Z]/', $p)) {

                $valid = 0;
                array_push($errors,"Your password must contain at least one uppercase letter.");

              }

              if ($valid) {

                $uId = $id;

                $stmt->close();

                $uQ = "UPDATE users SET password=? WHERE id=?";

                $protP = crypt($p, '$2a$07$5jh843257hquiyo7ghfkgi$');

                if ($uStmt = $con->prepare($uQ)) {

                  $uStmt->bind_param("si",$protP,$uId);

                  if ($uStmt->execute()) {

                    $uStmt->close();

                    $dQ = "DELETE FROM recover WHERE receipt=?";

                    if ($dStmt = $con->prepare($dQ)) {

                      $dStmt->bind_param("s",$receipt);

                      if ($dStmt->execute()) {

                        echo "<p style='text-align:center;'>Your password has been reset. Try logging in through the app!</p>";

                      }

                      $dStmt->close();

                    }

                  }

                }

              }

            }

            if (!$valid) {
            ?>
              <form action="" method="post" style="width:60%;margin:auto;text-align:center;">

                <h5>Enter New Password:</h5>
                <div class="form-group has-feedback">
                  <input placeholder="Choose something good..." class="form-control" type="password" name="password" >
                  <span class="form-control-feedback fui-user"></span>
                </div>

                <h5>Repeat Password:</h5>
                <div class="form-group has-feedback">
                  <input placeholder="...and repeat it here!" class="form-control" type="password" name="passwordConfirm" >
                  <span class="form-control-feedback fui-user"></span>
                </div>

                <input type="submit" name="submit" class="btn btn-primary btn-reduse-on-xs" value="Reset Password">


              </form>
            <?php
            }
          }
        }
      }
    }
    ?>

    <?php
    $con->close();
    ?>
  </div>
</body>
</html>
