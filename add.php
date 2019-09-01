<html>
<head>
  <meta charset="utf-8">
  <title>CollectMemes</title>
  <meta name="description" content="Build a personal collection of the world's most popular memes, and share with your friends!"/>

  <meta name="viewport" content="width=1000, initial-scale=1.0, maximum-scale=1.0">

  <!-- Loading Bootstrap -->
  <link href="dist/css/vendor/bootstrap.min.css" rel="stylesheet">

  <!-- Loading Flat UI -->
  <link href="dist/css/flat-ui.css" rel="stylesheet">
  <link href="docs/assets/css/demo.css" rel="stylesheet">

  <link rel="shortcut icon" href="dist/favicon.ico">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
  <!--[if lt IE 9]>
    <script src="dist/js/vendor/html5shiv.js"></script>
    <script src="dist/js/vendor/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <div class="container">

<nav class="navbar navbar-inverse navbar-expand-lg" role="navigation" style="margin-top:100px;">
  <a href="http://collectmemes.com"><span class="navbar-brand fui-arrow-left"></span></a><a class="navbar-brand" href="#" style="padding-left:0px;">Submit Meme</a>

</nav><!-- /navbar -->
<p style="text-align:center;">While our app and website aren't live yet, you can contribute to the database that will launch with the app.</p>
<?php

require 'access/db.php';

$isSubmitted = 0; // Variable for figuring out whether the user made an error.
$memeID = 0; // Variable for grabbing meme ID later.

if (isset($_POST['submit'])) {

	// Fields required: title, image, source, creator, edition.
	if (!empty($_POST['title']) and !empty($_POST['image']) and !empty($_POST['source']) and !empty($_POST['creator'])) {



		// Calculate rank.
		$grabRank = $con->query("SELECT COUNT(*) FROM memes");
		$grabRankRow = $grabRank->fetch_row();

		// Build meme profile.
		$title = $con->real_escape_string($_POST['title']);
		$image = $con->real_escape_string($_POST['image']);
		$totalOwned = 0;
		$rank = $grabRankRow[0] + 1;
		$inRotation = 1;
		$edition = 1;
		$source = $con->real_escape_string($_POST['source']);
		$creator = $con->real_escape_string($_POST['creator']);
		$dateAdded = date("Y-m-d");

		$q = "INSERT INTO `memes` (`title`, `image`, `totalOwned`, `rank`, `inRotation`, `edition`, `source`, `creator`, `dateAdded`) VALUES ('".$title."','".$image."','".$totalOwned."','".$rank."','".$inRotation."','".$edition."','".$source."','".$creator."','".$dateAdded."')";

		if (!$con->query($q)) {

			// Insert failed. Report error.
			echo "There was a problem with your insert (" . $con->errno . "): " . $con->error;

		} else {

			// Insert passed!
			$isSubmitted = 1;
      header("Location: /add?success=true");
      die();

		}

	} else {

		// Not all fields were supplied.
		echo '<div style="width:50%;margin:auto;padding:10px;background:#f99287;border:1px solid #e74c3c;border-radius:10px;text-align:center;">Not all fields were supplied!</div>';

	}

}

if (isset($_GET['success'])) {
  echo "<div style='width:50%;margin:auto;background:#87f9a9;padding:10px;border:1px solid #2ecc71;border-radius:10px;text-align:center;'>Meme successfully inserted!</div>";
}
?>
		<form action="" method="post" style="width:60%;margin:auto;text-align:center;">
    <h5>Title:</h5>
      <div class="form-group has-feedback">
  <input placeholder="Got a snappy title?" class="form-control" type="text" name="title" <?php if (isset($_POST['submit']) and $isSubmitted == 0) { echo "value='" . $_POST['title'] . "'";} ?>>
  <span class="form-control-feedback fui-info-circle"></span>
</div>
<h5>Image URL:</h5>
<div style="width:100%;display:block;min-height:16%;">
  <div style="float:left;width:50%;display:inline-block;">
        <div class="form-group has-feedback">
     <input placeholder="Enter URL ending in .png or .jpg" class="form-control"  type="text" id="image" name="image" <?php if (isset($_POST['submit']) and $isSubmitted == 0) { echo "value='" . $_POST['image'] . "'";} ?>>
    <span class="form-control-feedback fui-list"></span>
  </div>
  </div>
  <div style="float:right;width:33%;display:inline-block;">
    <script type="text/javascript">
  		jQuery(document).ready(function($) {

  			$('#image').bind('input', function() {
  			    $('#imageHolder').attr('src', $(this).val()); //concatinate path if required
  			});

  		});
  	</script>
	<img style="max-width:75%" name="imageHolder" id="imageHolder" src="https://cdn.pixabay.com/photo/2012/04/23/15/46/question-38629_960_720.png">
  </div>
</div>
<h5>Source:</h5>
  <div class="form-group has-feedback">
<input placeholder="Reddit, Imgur, OC, etc." class="form-control"  type="text" name="source" <?php if (isset($_POST['submit']) and $isSubmitted == 0) { echo "value='" . $_POST['source'] . "'";} ?>>
<span class="form-control-feedback fui-location"></span>
</div>
	<h5>Creator:</h5>
<div class="form-group has-feedback">
<input placeholder="ex. RedditUser" class="form-control" type="text" name="creator" <?php if (isset($_POST['submit']) and $isSubmitted == 0) { echo "value='" . $_POST['creator'] . "'";} ?>>
<span class="form-control-feedback fui-user"></span>
</div>
<br />

		<input type="submit" name="submit" class="btn btn-primary btn-reduse-on-xs" value="Submit Meme">
		</form>

<?php
$con->close();
?>
</div>
</body>
</html>
