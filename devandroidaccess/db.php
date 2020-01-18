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

function giveXP($userId, $amount, $con) {

	$ret = 0;

	$q = "UPDATE users SET xp=xp+? WHERE id=?";

	if ($s = $con->prepare($q)) {

		$s->bind_param("ii",$amount,$userId);

		if ($s->execute()) {

			$ret = 1;

		}

		$s->close();

	}

	return $ret;

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
		$info['rarityColor'] = "#e74c3c";
		$info['rarity'] = "Legendary";
		$info['rarityImage'] = "file://rarities/legendary.png";
		$info['rarityLining'] = "file://shared/legendaryL.png";

	} else if ($ratio > 0.07 and $ratio <= 0.19) {

		// Meme is Epic.
		$info['rarityColor'] = "#e68800";
		$info['rarity'] = "Epic";
		$info['rarityImage'] = "file://rarities/epic.png";
		$info['rarityLining'] = "file://shared/epicL.png";

	} else if ($ratio > 0.19 and $ratio <= 0.28) {

		// Meme is Rare.
		$info['rarityColor'] = "#8e44ad";
		$info['rarity'] = "Rare";
		$info['rarityImage'] = "file://rarities/rare.png";
		$info['rarityLining'] = "file://shared/rareL.png";

	} else if ($ratio > 0.28 and $ratio <= 0.58) {

		// Meme is Uncommon.
		$info['rarityColor'] = "#3498db";
		$info['rarity'] = "Uncommon";
		$info['rarityImage'] = "file://rarities/uncommon.png";
		$info['rarityLining'] = "file://shared/uncommonL.png";

	} else if ($ratio > 0.58 and $ratio <= 1) {

		// Meme is Common.
		$info['rarityColor'] = "#a9a9a9";
		$info['rarity'] = "Common";
		$info['rarityImage'] = "file://rarities/common.png";
		$info['rarityLining'] = "file://shared/commonL.png";

	}

	return $info;

}


function generateRank($con) {

	$dbSize = (int) getMemeCount($con);

	return round((mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax()) * $dbSize);

}

function xpToLevel($xp) {

	$b = 150;

	return floor(0.5*((sqrt($b+(8*$xp))/sqrt($b))-1));

}

function nextLevelXpNeeded($currentLevel) {

	$b = 150;

	$c = intval($currentLevel) + 1;

	return ((($c*($c+1)) / 2) * $b);

}

?>
