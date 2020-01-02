<?php
require "access/db.php";

$users = "";
$spins = "";
$collected = "";

$usersQ = "SELECT id FROM users WHERE 1";
$spinsQ = "SELECT SUM(totalSpins) FROM users WHERE 1";
$collectedQ = "SELECT id FROM owns WHERE 1";

if ($u = $con->prepare($usersQ)) {

  $u->execute();
  $u->store_result();

  $users = number_format($u->num_rows);

  $u->close();

}

if ($s = $con->prepare($spinsQ)) {

  $s->execute();

  $s->bind_result($t);

  $s->fetch();

  $spins = number_format($t);

  $s->close();

}

if ($c = $con->prepare($collectedQ)) {

  $c->execute();

  $c->store_result();

  $collected = number_format($c->num_rows);

  $c->close();

}

 ?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CollectMemes</title>
    <meta name="description" content="Build a personal collection of the world's most popular memes, and share with your friends!"/>

    <meta name="viewport" content="width=1000">

    <!-- Loading Bootstrap -->
    <link href="dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="dist/css/flat-ui.css" rel="stylesheet">
    <link href="docs/assets/css/demo.css" rel="stylesheet">

    <link rel="shortcut icon" href="dist/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="dist/js/vendor/html5shiv.js"></script>
      <script src="dist/js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="demo-headline" style="padding-bottom:10px;">
        <h1 class="demo-logo">
          <div class="logo"></div>
          CollectMemes
          <small style="font-size:40px;">Literally just collect memes.</small>
        </h1>
      </div> <!-- /demo-headline -->

      <div style="text-align:center;">
        <h3>Alpha is now available on Android only:</h3>
        <a href='https://play.google.com/store/apps/details?id=com.collectmemes&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img style="width:50%" alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png'/></a>
        <h4 style="padding-bottom:40px;">The Alpha may contain bugs.<br>A massive Beta update will be releasing in January 2020.</h4>
      </div>

      <div class="row demo-tiles" style="width:90%;margin:auto;">

        <div class="col">
          <div class="tile tile-hot">
            <img src="docs/assets/images/icons/girl.svg" alt="ribbon" class="tile-image">
            <h3 class="tile-title"><?php echo $users; ?></h3>
              <p style="width:90%;margin-left:auto;margin-right:auto;font-size:20px;">active users</p>
          </div>
        </div>

        <div class="col">
          <div class="tile tile-hot">
            <img src="docs/assets/images/icons/loop.svg" alt="Pensils" class="tile-image">
            <h3 class="tile-title"><?php echo $spins; ?></h3>
            <p style="width:90%;margin-left:auto;margin-right:auto;font-size:20px;">total user spins</p>
          </div>
        </div>

        <div class="col">
          <div class="tile tile-hot">
            <img src="docs/assets/images/icons/clipboard.svg" alt="Chat" class="tile-image">
            <h3 class="tile-title"><?php echo $collected; ?></h3>
            <p style="width:90%;margin-left:auto;margin-right:auto;font-size:20px;">memes collected</p>
          </div>

        </div>

      </div> <!-- /tiles -->


    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <!-- Bootstrap 4 requires Popper.js -->
    <script src="https://unpkg.com/popper.js@1.14.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="dist/scripts/flat-ui.js"></script>
    <script src="docs/assets/js/application.js"></script>

  </body>
</html>
<?php $con->close();
?>
