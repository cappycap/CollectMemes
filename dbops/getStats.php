<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

// Replace w/ requirement for communication with cronjob
if (1) {

	// Grab components: time, memes, users, xp, spins, likes, owned, pro
	$time = time();

	$memes = getMemeCount($con);

	$users = 0;

	$usersQ = "SELECT id FROM users";

	if ($usersS = $con->prepare($usersQ)) {

		$usersS->execute();

		$usersS->store_result();

		$users = $usersS->num_rows;

		$usersS->close();

	}

	$xp = 0;

	$xpQ = "SELECT SUM(xp) FROM users";

	if ($xpS = $con->prepare($xpQ)) {

		$xpS->execute();

		$xpS->bind_result($total);

		$xpS->fetch();

		$xp = $total;

		$xpS->close();

	}

	$spins = 0;

	$spinsQ = "SELECT SUM(totalSpins) FROM users";

	if ($spinsS = $con->prepare($spinsQ)) {

		$spinsS->execute();

		$spinsS->bind_result($total);

		$spinsS->fetch();

		$spins = $total;

		$spinsS->close();

	}

	$likes = 0;

	$likesQ = "SELECT SUM(likesSize) FROM users";

	if ($likesS = $con->prepare($likesQ)) {

		$likesS->execute();

		$likesS->bind_result($total);

		$likesS->fetch();

		$likes = $total;

		$likesS->close();

	}

	$owned = 0;

	$ownedQ = "SELECT SUM(collectionSize) FROM users";

	if ($ownedS = $con->prepare($ownedQ)) {

		$ownedS->execute();

		$ownedS->bind_result($total);

		$ownedS->fetch();

		$owned = $total;

		$ownedS->close();

	}

	$pro = 0;

	$proQ = "SELECT SUM(isPro) FROM users";

	if ($proS = $con->prepare($proQ)) {

		$proS->execute();

		$proS->bind_result($total);

		$proS->fetch();

		$pro = $total;

		$proS->close();

	}

	$q = "INSERT into stats (time, memes, users, xp, spins, likes, owned, pro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

	if ($s = $con->prepare($q)) {

		$s->bind_param("iiiiiiii",$time,$memes,$users,$xp,$spins,$likes,$owned,$pro);

		if ($s->execute()) {

			$response['success'] = 1;

		} else {

			$response['success'] = 0;

		}

	}

}

echo json_encode($response);

$con->close();
?>
