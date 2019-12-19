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
<table>
<tr>
  <th>Image</th>
  <th>ID</th>
</tr>
<?php

$q = "SELECT id,image FROM memes";

if ($s = $con->prepare($q)) {

  $s->execute();

  $s->bind_result($id,$image);
  while ($s->fetch()) {

    echo "<tr><td><img src='" . $image . "'></td><td>" . $id . "</td></tr>";

  }

  $s->close();

}
?>
</table>
</body>
</html>
<?php

$con->close();

?>
