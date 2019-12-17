<?php

function calc() {

	// First and max level.
	$c = 1;
	$m = 100;

	// Base XP for level 1.
	$b = 150;

	while ($c <= $m) {

		$a = (($c*($c+1)) / 2) * $b;
		$i = ((($c*($c+1)) / 2) * $b) - ((($c*($c-1)) / 2) * $b);

		$a2 = $a;

		$l = floor(0.5*((sqrt($b+(8*$a2))/sqrt($b))-1));

		echo $c . "- " . $a . " XP (" . $i . " increase) --- " . $a2 . " XP: Level " . $l . "<br>";

		$c++;

	}

}
?>

Spin - 20 XP each (10 per hour, 15 for Pro users), one-time $5 payment for Pro<br>
Collect - 10 XP<br>
Upload - 1000 XP (per 50 spins, you get 1 upload token)<br>
Challenges - 500-1000 XP (depending on difficulty)<br>
Achievements - varying<br>
Add Friend - 1000 XP<br>
<br>

<?php
calc();
?>
