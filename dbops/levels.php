<?php

function calc() {

	// First and max level.
	$c = 1;
	$m = 99;

	// Base XP for level 1.
	$b = 100;

	while ($c <= 99) {

		$a = (($c*($c+1)) / 2) * $b;
		$i = ((($c*($c+1)) / 2) * $b) - ((($c*($c-1)) / 2) * $b);

		$a2 = $a;

		$l = floor(0.5*((sqrt($b+(8*$a2))/sqrt($b))-1));

		echo $c . "- " . $a . " XP (" . $i . " increase) --- " . $a2 . " XP: Level " . $l . "<br>";

		$c++;

	}

}
?>

Spin - 5 XP each (10 per hour, 15 for Pro users), one-time $5 payment for Pro<br>
Collect - 50 XP<br>
Upload - 500 XP (per 50 spins, you get 1 upload token)<br>
Challenges - 100-500 XP (depending on difficulty)<br>
Achievements - 100 XP each<br>
Add Friend - 500 XP<br>
<br>

<?php
calc();
?>
