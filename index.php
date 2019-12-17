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

    <meta name="viewport" content="width=1000, initial-scale=1.0, maximum-scale=1.0">

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
      <div class="demo-headline">
        <h1 class="demo-logo">
          <div class="logo"></div>
          CollectMemes
          <small>Literally just collect memes.</small>
        </h1>
      </div> <!-- /demo-headline -->

      <div class="row demo-tiles">

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

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-7" style="padding-left:40px;">
            <h3 class="footer-title">About CollectMemes</h3>
            <p>This is a small project created by a student developer.<br/>
              Check back in to see how the project develops!
            </p>

            <p class="pvl">
              <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://collectmemes.com/" data-text="I'm excited to start collecting some memes!" data-via="collect_memes">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            </p>

            <a class="footer-brand" href="http://designmodo.com" target="_blank">
              <img src="docs/assets/images/demo/logo.png" alt="Designmodo.com" />
            </a>
          </div> <!-- /col-7 -->

          <div class="col-5">
            <div class="footer-banner">
              <h3 class="footer-title">Why I'm doing this:</h3>
              <ul>
                <li>It's pretty fun!</li>
                <li>Automated feedback practice.</li>
                <li>Who doesn't love ranking?</li>
              </ul>
              Go to <a href="http://adambullard.com">adambullard.com</a> to learn more.
            </div>
          </div>
        </div>
      </div>
    </footer>

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
