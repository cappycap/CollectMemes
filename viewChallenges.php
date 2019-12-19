<?php

require "access/db.php";

 ?>
<html>
<head>
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
      text-align:center;
    }

    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
      vertical-align:middle;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }

    img {
      width:100px;
      height:100px;
    }
  </style>
</head>
<body>
<?php

$challenges = array();

$selQ = "SELECT id,title,memes FROM collections";

if ($selS = $con->prepare($selQ)) {

  $selS->execute();

  $selS->bind_result($id,$title,$memes);

  while ($selS->fetch()) {

    $challenge = array();

    $challenge['id'] = $id;
    $challenge['title'] = $title;
    $challenge['memes'] = $memes;

    $challenges[] = $challenge;

  }

  $selS->close();

}

foreach($challenges as $chal) {

  echo "<h1>#" . $chal['id'] . ": " . $chal['title'] . "</h1>";

  $memes = explode(",",$chal['memes']);

  echo "<div>";

  foreach($memes as $meme) {

    $q = "SELECT title,image FROM memes WHERE id=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("i",$meme);

      $s->execute();

      $s->bind_result($title,$image);

      if ($s->fetch()) {

        echo "<div style='width:33%;display:inline-block;text-align:center;'>" . $title . "<br><img src='" . $image . "'></div>";

      }

      $s->close();

    }

  }

  echo "</div>";

}
 ?>
</body>
</html>
<?php

$con->close();

?>
