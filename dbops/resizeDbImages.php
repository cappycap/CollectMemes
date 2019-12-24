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

function minImage($filename) {

	// The file
	$filename = 'test.jpg';
	$percent = 0.5;

	// Content type
	header('Content-Type: image/jpeg');

	// Get new dimensions
	list($width, $height) = getimagesize($filename);
	$new_width = $width * $percent;
	$new_height = $height * $percent;

	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	$dir = "min/" . $filename;

	$ret = 0;

	if (imagejpeg($im, 'simpletext.jpg')) {

		$ret = 1;

	}

	return $ret;

}

function imageAlreadyMin($filename) {

	return 0;

}
// Define response array for delivering status.
$response = array();

$images = array();

$getQ = "SELECT image FROM memes";

if ($get = $con->prepare($getQ)) {

	if ($get->execute()) {

		$get->bind_result($image);

		while ($get->fetch()) {

			$filenameArray = explode("https://collectmemes.com/img/memes/",$image);
			$filename = $filenameArray[0];

			$images[] = $filename;

		}

		$get->close();

	}

}

foreach($images as $filename) {

	$exist = imageAlreadyMin($filename);

	if (!$exist) {

		minImage($filename);

	}

}

echo json_encode($response);

$con->close();
?>
