<?php

require 'db.php';

function getAchievementArray($string, $con) {

	$achievementCount = 1;

	$achievements = array();

	$achievementsNum = explode(",",$string);

	$currentAchievementId = 0;

	while ($currentAchievementId < $achievementCount) {

		$currentStage = $achievementsNum[$currentAchievementId];

		$q = "SELECT isFinal,isStaged,title,reqs,image,xp FROM achievements WHERE achievementId=? AND stage=?";

		if ($s = $con->prepare($q)) {

			$s->bind_param("ii",$currentAchievementId,$currentStage);

			$s->execute();

			$s->bind_result($isFinal,$isStaged,$title,$reqs,$image,$xp);

			if ($s->fetch()) {

				$a = array();

				$a['isFinal'] = $isFinal;
				$a['isStaged'] = $isStaged;
				$a['title'] = $title;
				$a['reqs'] = $reqs;
				$a['image'] = $image;
				$a['xp'] = $xp;

				$achievements[] = $a;

			}

			$s->close();

		}

		$currentAchievementId++;

	}

	return $achievements;

}

echo json_encode(getAchievementArray("0",$con), JSON_UNESCAPED_SLASHES);

?>
