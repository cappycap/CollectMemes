<?php

// Database information.
define('DB_USER', "adamvxoc_phpaccess"); // db user
define('DB_PASSWORD', "jUJ16efs04X+"); // db password (mention your db password here)
define('DB_DATABASE', "adamvxoc_memecollector"); // database name
define('DB_SERVER', "localhost"); // db server

$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

// Check connection
if($con->connect_errno) {
	printf("Connect failed: %s\n", $con->connect_error);
    exit();
}

// Function for searching for a target within a collection returned from DB storage format.
function inCollection($collection, $target) {

	// String array in form "1,2,3,4"
	$array = explode(",", $collection);

	// Return result.
	return in_array($target, $array);

}

function getMemeCount($con) {

	$rows = 0;

	$query = $con->prepare("SELECT id FROM memes");

	$query->execute();

	$query->store_result();

	$rows = $query->num_rows;

	$query->free_result();

	$query->close();

	return $rows;

}
// Function for returning an array pack for naming, colorizing.
// Should give: Badge Image, Colors for UI
function getRankInfo($memeRank, $con) {

	$ratio = (float) intval($memeRank) / (float) getMemeCount($con);
	$info = array();

	if ($ratio <= 0.07) {

		// Meme is Legendary.
		$info['color'] = "#e74c3c";
		$info['rarity'] = "Legendary";
		$info['font-size'] = "2vw";
		$info['font-size-view'] = "18";
		$info['file'] = "file://legendary.png";

	} else if ($ratio > 0.07 and $ratio <= 0.19) {

		// Meme is Epic.
		$info['color'] = "#FAB657";
		$info['rarity'] = "Epic";
		$info['font-size'] = "3vw";
		$info['font-size-view'] = "22";
		$info['file'] = "file://epic.png";

	} else if ($ratio > 0.19 and $ratio <= 0.28) {

		// Meme is Rare.
		$info['color'] = "#8e44ad";
		$info['rarity'] = "Rare";
		$info['font-size'] = "3vw";
		$info['font-size-view'] = "22";
		$info['file'] = "file://rare.png";

	} else if ($ratio > 0.28 and $ratio <= 0.58) {

		// Meme is Uncommon.
		$info['color'] = "#a3ffb9";
		$info['rarity'] = "Uncommon";
		$info['font-size'] = "2vw";
		$info['font-size-view'] = "18";
		$info['file'] = "file://uncommon.png";

	} else if ($ratio > 0.58 and $ratio <= 1) {

		// Meme is Common.
		$info['color'] = "#ffffff";
		$info['rarity'] = "Common";
		$info['font-size'] = "3vw";
		$info['font-size-view'] = "18";
		$info['file'] = "file://common.png";

	}

	return $info;

}


function generateRank($con) {

	$dbSize = (int) getMemeCount($con);

	return round((mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax()) * $dbSize);

}

?>
