<?php

// Database information.
define('DB_USER', "adamvxoc_phpaccess"); // db user
define('DB_PASSWORD', "22pwjD6T8zvZ"); // db password (mention your db password here)
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

// Function for returning an array pack for naming, colorizing.
function getRankInfo($memeRank, $dbSize) {

	$ratio = intval($memeRank) / intval($dbSize);
	$info = array();

	if ($ratio <= 0.03) {

		// Meme is Legendary.

	} else if ($ratio > 0.03 and $ratio <= 0.13) {

		// Meme is Epic.

	} else if ($ratio > 0.13 and $ratio <= 0.28) {

		// Meme is Rare.

	} else if ($ratio > 0.28 and $ratio <= 0.58) {

		// Meme is Uncommon.

	} else if ($ratio > 0.58 and $ratio <= 1) {

		// Meme is Common.

	}

}

?>
