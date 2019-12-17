<?php
header('Content-Type: application/json');

require 'db.php';

function getAchievementArray($string, $con) {

	$achievementCount = 15;

	$achievements = array();

	$achievementsNum = explode(",",$string);

	$currentAchievementId = 0;

	while ($currentAchievementId < $achievementCount) {

		$currentStage = $achievementsNum[$currentAchievementId];

		$q = "SELECT isFinal,isStaged,stageMsg,title,reqs,image,xpNext FROM achievements WHERE achievementId=? AND stage=?";

		if ($s = $con->prepare($q)) {

			$s->bind_param("ii",$currentAchievementId,$currentStage);

			$s->execute();

			$s->bind_result($isFinal,$isStaged,$stageMsg,$title,$reqs,$image,$xp);

			if ($s->fetch()) {

				$a = array();

				$a['isFinal'] = $isFinal;
				$a['isStaged'] = $isStaged;
				if ($isStaged) {
					$a['stageMsg'] = $stageMsg;
				}
				$a['title'] = $title;
				$a['reqs'] = $reqs;
				$a['image'] = $image;
				$a['xpNext'] = "+" . number_format($xp) . " XP";

				$achievements[] = $a;

			}

			$s->close();

		}

		$currentAchievementId++;

	}

	return $achievements;

}

echo json_encode(getAchievementArray("2,1,1,1,1,0,0,0,0,0,0,0,0,0,0",$con), JSON_UNESCAPED_SLASHES);

?>
