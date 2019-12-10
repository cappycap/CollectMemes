<?php

require 'db.php';
// Define response array for delivering status.
$response = array();
$check = false;

if (isset($_POST['userId']) and isset($_POST['pass']) and isset($_POST['spinsLeft'])) {

  if ($_POST['pass'] == "933kfjhga7862344bv") {

    $meme = array();

    $count = 0;

    if ((int)$_POST['spinsLeft'] == 10) {

      $meme['id'] = -1;
      $meme['title'] = "Spin to get started!";
      $meme['image'] = "https://collectmemes.com/img/start.png";

    } else {

      $userId = $con->real_escape_string($_POST['userId']);

      // update totalSpins.
      $uTSQ = "UPDATE users SET totalSpins = totalSpins+1 WHERE id=?";

      if ($uTSQS = $con->prepare($uTSQ)) {

        $uTSQS->bind_param("i",$userId);

        $uTSQS->execute();

        $uTSQS->close();

      }

      while ($count < 1) {

        $rank = (int) generateRank($con);

        // Check if rank has already been claimed by user.
        $checkQ = "SELECT memeId FROM owns WHERE rank=? AND userId=?";

        if ($checkS = $con->prepare($checkQ)) {

          $checkS->bind_param("ii",$rank,$userId);

          $checkS->execute();

          $checkS->store_result();

          if ($checkS->num_rows == 0) {

            $check = true;

          }

          $checkS->free_result();

          $checkS->close();

        }

        if ($check) {

          // We good, query meme.
          $memeQuery = "SELECT id,title,image,source,creator FROM memes WHERE rank = ?";

          if ($stmt = $con->prepare($memeQuery)) {

            $stmt->bind_param("i",$rank);

            $stmt->execute();

            $stmt->bind_result($id,$title,$image,$source,$creator);

            if ($stmt->fetch()) {

              $idString = (string) $id;

              $meme['id'] = $id;
              $meme['title'] = $title;
              $meme['image'] = $image;
              $meme['rank'] = $rank;
              $meme['source'] = $source;
              $meme['creator'] = $creator;

              $count = $count + 1;

              $stmt->free_result();

              $stmt->close();

              $checkLike = "SELECT memeId FROM likes WHERE rank=? AND userId=?";

              if ($like = $con->prepare($checkLike)) {

                $like->bind_param("ii",$rank,$userId);

                $like->execute();

                $like->store_result();

                if ($like->num_rows == 0) {

                  $response['liked'] = 0;

                } else {

                  $response['liked'] = 1;

                }

                $like->free_result();

                $like->close();

              }

            } else {

              $stmt->free_result();

              $stmt->close();

            }

          }

        }

      }

    }

    if ($meme['id'] == -1) {

      $response['html'] = "<html>
      <head>
      <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
      <style>

        .center {
          width:100%;
          margin:0;
          padding:0;
        }

        body {
          background:#111111;margin:0;height:100%;
        }

        .num {
          color:green;
        }

      .buttons {
        width:100%;
        height:12%;
        margin-top:6%;
        color:#fff;
        padding:0;
        font-size:6vw;
        text-align:center;
      }

      .spin-again {
        display:inline-block;float:left;width:48%;height:100%;background:#34495e;border-radius:0px 35px 35px 0px;
      }

      .collect-meme {
        display:inline-block;float:right;width:48%;height:100%;background:#3b9fdf;border-radius:35px 0px 0px 35px;
      }

      .centered-text {
        display: table;
          height: 100%;
          width: 100%;
      }

      .centered-text span {
        display: table-cell;
          vertical-align: middle;
      }

      .box {
      display: flex;
      width: 100%;
      height: 8px;
      margin: 0px 0px 0px 0px;
      }

      .box-sm {
      height: 8px;
      margin: 0;
      flex-grow: 1;
      transition: all .8s ease-in-out;
      cursor: pointer;
      }
      .out {
      background-color: #57bbfb;
      }

      .mid {
      background-color: #3b9fdf;
      }

      .centerb {
      background-color: #1a7ebe;
      }
      .info {
      width:100%;
      text-align:center;
      display:table;
      margin:20px 0px;
      padding:0;
      height:60px;
      color:#111111;
      }
      .section-right {
      display:table-cell;
      width:16%;
      background:#111111;
      vertical-align:middle;
      padding:4px;
      font-size:20px;
      }

      .section-middle {
      display:table-cell;
      width:68%;
      background:#0f0f0f;
      color:white;
      font-size:20px;
      vertical-align:middle;
      }

      .section-left {
      display:table-cell;
      width:16%;
      background:#111111;
      color:white;
      vertical-align:middle;
      padding:4px;
      font-size:16px;
      }
      .source {
      font-size:15px;
      }
      .img {
      width:80%;
      margin:auto;
      }
      </style>
      </head>
      <body>
      <div class='info'>
        <div class='section-left'>
          <div class='inner'></div>
        </div>
        <div class='section-middle'>
          <div class='title'>" . $meme['title'] . "</div>
        </div>
        <div class='section-right'>
          <div class='inner'></div>
        </div>
      </div>
      <div class='img'>
        <img class='rotate-scale-up center child' src='" . $meme['image'] . "'>
      </div>
      <script>
      var cw = $('.child').width();
      $('.child').css({'height':cw+'px'});
      </script>
      </body></html>";

    } else {

      $info = getRankInfo($meme['rank'], $con);

      $response['html'] = "<html>
      <head>
      <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
      <style>
        .rotate-scale-up {
          animation: rotate-scale-up 0.1s linear both;
        }

        @keyframes rotate-scale-up {
          0% {
            transform: scale(3) rotateZ(0);
          }
          50% {
            transform: scale(2) rotateZ(180deg);
          }
          100% {
            transform: scale(1) rotateZ(360deg);
          }
        }

        .center {
          width:100%;
          margin:0;
          padding:0;
        }

        body {
          background:#111111;margin:0;height:100%;
        }

        .num {
          color:green;
        }

        .tracking-in-expand-fwd {
          left:0;
          animation: tracking-in-expand-fwd 1.5s cubic-bezier(0.215, 0.610, 0.355, 1.000) 0.1s both;
          width:100%;
          text-align:center;
          color:#111111;
          font-size:10vw;
          margin:0;
          padding:3% 0;
        }

        @keyframes tracking-in-expand-fwd {
        0% {
          letter-spacing:-0.5em;
          transform: translateZ(-700px);
          opacity: 0;
        }
        50% {
          transform: translateZ(0);
          opacity: 1;
        }
        100% {
          transform: translateZ(-700px);
          opacity: 0;
        }
      }

      .buttons {
        width:100%;
        height:12%;
        margin-top:6%;
        color:#fff;
        padding:0;
        font-size:6vw;
        text-align:center;
      }

      .spin-again {
        display:inline-block;float:left;width:48%;height:100%;background:#34495e;border-radius:0px 35px 35px 0px;
      }

      .collect-meme {
        display:inline-block;float:right;width:48%;height:100%;background:#3b9fdf;border-radius:35px 0px 0px 35px;
      }

      .centered-text {
        display: table;
          height: 100%;
          width: 100%;
      }

      .centered-text span {
        display: table-cell;
          vertical-align: middle;
      }

      .box {
      display: flex;
      width: 100%;
      height: 8px;
      margin: 0px 0px 0px 0px;
      }

      .box-sm {
      height: 8px;
      margin: 0;
      flex-grow: 1;
      transition: all .8s ease-in-out;
      cursor: pointer;
      }
      .out {
      background-color: #57bbfb;
      }

      .mid {
      background-color: #3b9fdf;
      }

      .centerb {
      background-color: #1a7ebe;
      }
      .info {
      width:100%;
      text-align:center;
      display:table;
      margin:20px 10px;
      padding:0;
      height:60px;
      color:#111111;
      }
      .section-right {
      display:table-cell;
      width:16%;
      background:#111111;
      vertical-align:middle;
      padding:4px;
      font-size:20px;
      }

      .section-middle {
      display:table-cell;
      width:68%;
      background:#0f0f0f;
      color:white;
      font-size:20px;
      vertical-align:middle;
      }

      .section-left {
      display:table-cell;
      width:16%;
      background:#111111;
      color:white;
      border:3px solid " . $info['color'] . ";
      vertical-align:middle;
      padding:4px;
      font-size:16px;
      }
      .source {
      font-size:15px;
      }
      .img {
      width:80%;
      margin:auto;
      }
      </style>
      </head>
      <body>
      <div class='info'>
        <div class='section-left'>
          <div class='inner'>#" . $meme['rank'] . "<br><span style='color:" . $info['color'] . ";font-size:" . $info['font-size'] . ";'>" . $info['rarity'] . "</span></div>
        </div>
        <div class='section-middle'>
          <div class='title'>" . $meme['title'] . "</div>
          <div class='source'>
            by " . $meme['creator'] . "
          </div>
        </div>
        <div class='section-right'>
          <div class='inner'></div>
        </div>
      </div>
      <div class='img'>
        <img class='rotate-scale-up center child' src='" . $meme['image'] . "'>
      </div>
      <script>
      var cw = $('.child').width();
      $('.child').css({'height':cw+'px'});
      </script>
      </body></html>";
    }

    $response['id'] = $meme['id'];

  }

}

echo json_encode($response);

$con->close();
?>
