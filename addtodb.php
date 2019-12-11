<html>
<head>
<style>
.slidecontainer {
width: 200px;
margin:auto;
}

.slider {
-webkit-appearance: none;
width: 100%;
height: 25px;
background: #d3d3d3;
outline: none;
opacity: 0.7;
-webkit-transition: .2s;
transition: opacity .2s;
}

.slider:hover {
opacity: 1;
}

.slider::-webkit-slider-thumb {
-webkit-appearance: none;
appearance: none;
width: 25px;
height: 25px;
background: #4CAF50;
cursor: pointer;
}

.slider::-moz-range-thumb {
width: 25px;
height: 25px;
background: #4CAF50;
cursor: pointer;
}
body {
color:#fff;
background:#111111;
width:500px;
text-align:center;
margin:auto;
}
input {
padding:10px;
border-radius:10px;
width:200px;
margin-top:10px;
}
img {max-width:500px;}
</style>
</head><body>
<?php

require 'access/db.php';

if (isset($_POST['submit'])) {

  $title = $con->real_escape_string($_POST['title']);
  $image = $con->real_escape_string($_POST['image']);
  $totalOwned = 0;
  $likes = 0;
  $score = $con->real_escape_string($_POST['score']);
  $rank = getMemeCount($con) + 1;
  $inRotation = 1;
  $edition = 1;
  $creator = "CollectMemes";
  $dateAdded = time();
  $display = 1;

  $uploadQ = "INSERT INTO memes (title, image, totalOwned, likes, score, rank, inRotation, edition, creator, dateAdded, display)
   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  echo 'uploading meme...<br>';

  if ($uS = $con->prepare($uploadQ)) {

    $uS->bind_param("ssiiiiiisii",$title,$image,$totalOwned,$likes,$score,$rank,$inRotation,$edition,$creator,$dateAdded,$display);

    if ($uS->execute()) {

      echo "meme uploaded successfully.";

    } else {

      echo $uS->error;

    }

    $uS->close();

  }

}

$directory = 'img/memes';

    if (!is_dir($directory)) {
        exit('Invalid diretory path');
    }

    $files = array();
    foreach (scandir($directory) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }

   $c = count($files);

   $count = 0;
   $loopCount = 0;

   while ($loopCount < 1 and $count < $c) {

     $q = "SELECT id FROM memes WHERE image=?";

     $i = "https://collectmemes.com/img/memes/" . $files[$count];

     if ($s = $con->prepare($q)) {

       $s->bind_param("s",$i);

       $s->execute();

       $s->store_result();

       if ($s->num_rows == 0) {

         $loopCount++;

         echo "<img src='" . $i . "'/><br><form action='addtodb.php' method='post'>
         <input type='text' name='title' placeholder='title'><br>
         <input type='hidden' name='image' value='" . $i . "'><br>
         <div class='slidecontainer'>
           <input type='range' min='1' max='100' value='50' class='slider' id='score' name='score'>
           <p>Score: <span id='demo'></span></p>
         </div>
         <input type='submit' name='submit' value='submit' id='submit' />
         <script>
var slider = document.getElementById('score');
var output = document.getElementById('demo');
output.innerHTML = slider.value;

slider.oninput = function() {
  output.innerHTML = this.value;
}
</script>
         </form>
         ";

       } else {

         $count++;

       }

       $s->close();

     }

   }



$con->close();
?>

</body>
</html>
