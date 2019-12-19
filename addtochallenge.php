<?php

require "access/db.php";

if (isset($_POST['submit'])) {

  $c = $con->real_escape_string($_POST['collection']);
  $m = $con->real_escape_string($_POST['id']);

  $q = "SELECT memes,totalMemes,xpReward FROM collections WHERE id=?";

  if ($s1 = $con->prepare($q)) {

    $s1->bind_param("i",$c);

    $s1->execute();

    $s1->bind_result($memes,$totalMemes,$xpReward);

    if ($s1->fetch()) {

      $newTotalMemes = $totalMemes + 1;
      $newXpReward = $xpReward + 1000;
      $newMemes = "";

      if ($totalMemes == 0) {

        $newMemes = $m;

      } else {

        $newMemes = $memes . "," . $m;

      }

      $s1->close();

      $q2 = "UPDATE collections SET memes=?,totalMemes=?,xpReward=? WHERE id=?";

      if ($s2 = $con->prepare($q2)) {

        $s2->bind_param("siii",$newMemes,$newTotalMemes,$newXpReward,$c);

        if ($s2->execute()) {

          echo 'updated.<br>';

        } else {

          echo $s2->error;

        }

        $s2->close();

      }

    }

  }

}
?>

<form action="addtochallenge.php" method="post">
  Challenge Id:
  <select name='collection'>
   <option value='0'>None</option>
   <option value='1'>Obama</option>
   <option value='2'>Cone</option>
   <option value='3'>Music Gang</option>
   <option value='4'>Loss</option>
   <option value='5'>Chungus</option>
   <option value='6'>Wings</option>
   <option value='7'>Shaggy</option>
   <option value='8'>Minecraft</option>
   <option value='9'>Star Wars</option>
   <option value='10'>The Simpson's</option>
   <option value='11'>The Office</option>
   <option value='12'>Cute Animals</option>
   <option value='13'>:)</option>
   <option value='14'>Roblox</option>
   <option value='15'>Spongebob</option>
  </select>
  <br>
  meme ID:
<input type="text" name="id"
<br>
<input type='submit' name='submit' value='submit' id='submit' />
</form>
<?php

$con->close();

?>
