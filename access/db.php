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

?>
