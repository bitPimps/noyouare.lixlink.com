<?php
extract(array_merge($_POST,$_GET));
include("../sec/config/phpConfig.php");
include("../sec/config/common_db.php");
include("../sec/phpinclude/classes/PunchPad.php");
$msg = "";
// Connect to Database
$dbConn = connectNYRDb();
// Save
if($action=="Save") {
	$punchPad = new PunchPad("");
	if (isset($publish)) {
		$punchPad->setPublish("1");
	} else {
		$punchPad->setPublish("0");
	}

	if ($firstName == "") {
		$msg = "No first name? Really?";
	} elseif ($lastName == "") {
		$msg = "No last name? Really?";
	} elseif ($strike == "") {
		$msg = "What's the fuckin' strike, dude?";
	} elseif ($score == "") {
		$msg = "What's the score, man?";
	}

	$punchPad->setId($id);
	$punchPad->setFirstName(htmlentities($firstName, ENT_QUOTES));
	$punchPad->setLastName(htmlentities($lastName, ENT_QUOTES));
	$punchPad->setStrike(htmlentities($strike, ENT_QUOTES));
	$punchPad->setScore(htmlentities($score, ENT_QUOTES));
	$punchPad->setPublish($publish);

	if ($msg != "") {
		include("edit.php");
		return;
	}
	$msg = $punchPad->save($dbConn);
	// Close DB Connection
	mysqli_close($dbConn);
	
	if ($msg == "") {
		header("Location: index.php?msg=Result%20Saved.");
		die();
	} else {
		$msg = "Result was not saved.";
		include("edit.php");
		return;
	}
}
// Delete
elseif ($action=="Delete") {
	$punchPad = new PunchPad("");
	$msg = $punchPad->deletePunchPad($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);;

	if ($msg == "") {
		header("Location: index.php?msg=Result%20Deleted.");
		die();
	}
	else {
		$msg = "Could not delete result data.";
		include("edit.php");
		return;
	}
}
// Edit
elseif (!isset($punchPad) && isset($id)) {
	$punchPad = new PunchPad("");
	$punchPad = $punchPad->getPunchPad($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
// New
if (!isset($punchPad)) {
	$punchPad = new PunchPad("");
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
?>