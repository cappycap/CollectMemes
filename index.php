<!DOCTYPE html>
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
            <h3 class="tile-title">Create An Account</h3>
              <p style="width:90%;margin-left:auto;margin-right:auto;">Begin collecting memes and comparing with friends! Can you collect them all?</p>
            <a href="#fakelink" class="btn btn-info btn-large btn-block" disabled>Create Account</a>
          </div>
        </div>

        <div class="col">
          <div class="tile tile-hot">
            <img src="docs/assets/images/icons/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
            <img src="docs/assets/images/icons/pencils.svg" alt="Pensils" class="tile-image">
            <h3 class="tile-title">Add A Meme</h3>
            <p style="width:90%;margin-left:auto;margin-right:auto;">CollectMemes is just getting started. You can help by uploading to the database!</p>
            <a class="btn btn-primary btn-large btn-block" href="/add">Add Meme</a>
          </div>
        </div>

        <div class="col">
          <div class="tile tile-hot">
            <img src="docs/assets/images/icons/chat.svg" alt="Chat" class="tile-image">
            <h3 class="tile-title">Support Us</h3>
            <p style="width:90%;margin-left:auto;margin-right:auto;">Your likes, shares and comments help us! Follow us on your favorite platform and spread the news!</p>
            <a class="btn btn-primary btn-large btn-block" href="https://designmodo.com/flat" disabled>View Platforms</a>
          </div>

        </div>

      </div> <!-- /tiles -->

      <div class="row" style="text-align:center;">
        <div class="col-9">
          <div class="demo-browser" style="margin-bottom:0px;">
            <div class="demo-browser-side">
              <div class="demo-browser-author"></div>
              <h5 style="padding-top:10px;">@adamwbull</h5>
              <div class="demo-browser-action" style="padding-top:10px;">
                <a class="btn btn-info btn-block" href="http://CollectMemes.com/" target="_blank">
                  <span class="fui-plus"></span>Follow
                </a>
              </div>

              <h6 style="text-align:left;">
                Average Ranking:
                <br>
                <blockquote>
                  <p>103</p>
                </blockquote>
                <a class="btn btn-success btn-block" href="http://CollectMemes.com/" target="_blank">
                  Compare
                </a>

              </h6>
            </div>
            <div class="demo-browser-content">
              <img src="https://i.redd.it/mndr6okg3mi31.jpg" alt="" />
              <img src="https://i.redd.it/wnrlbnd5lli31.jpg" alt="" />
              <img src="https://i.redd.it/otl0hdo14ni31.jpg" alt="" />
              <img src="https://i.redd.it/y490xmftcyi31.jpg" alt="" />
              <img src="https://i.redd.it/wbliudtqyli31.jpg" alt="" />
              <img src="https://i.redd.it/gimc8p9yxdh31.jpg" alt="" />
            </div>
          </div>
          Desktop preview.
          <br>
          <br>
          <br>
          <br>
        </div>

        <div class="col-3">

          Mobile preview coming soon.
        </div>
      </div> <!-- /download area -->

    </div> <!-- /container -->

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-7">
            <h3 class="footer-title">About CollectMemes</h3>
            <p>This is a small project created by Adam Bullard.<br/>
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
