<?php
include 'db.php';

$rank = 12;

echo json_encode(getRankInfo($rank, $con));

?>
