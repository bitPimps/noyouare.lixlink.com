<?php
extract(array_merge($_POST,$_GET));
include("../sec/config/phpConfig.php");
include("../sec/config/common_db.php");
include("../sec/phpinclude/classes/Wolfknive.php");

$msg = "";

// Connect to Database
$dbConn = connectNYRDb();
// Save
if($action=="Save") {
	$wolfknive = new Wolfknive("");
	if (isset($publish)) {
		$wolfknive->setPublish("1");
	} else {
		$wolfknive->setPublish("0");
	}

	if ($name == "") {
		$msg = "What's the name?";
	} elseif ($sex == "") {
		$msg = "Is it for a male or female?";
	}

	$wolfknive->setId($id);
	$wolfknive->setName(htmlentities($name, ENT_QUOTES));
	$wolfknive->setSex(htmlentities($sex, ENT_QUOTES));
	$wolfknive->setPublish($publish);

	if ($msg != "") {
		include("edit.php");
		return;
	}
	$msg = $wolfknive->save($dbConn);
	// Close DB Connection
	mysqli_close($dbConn);
	
	if ($msg == "") {
		header("Location: index.php?msg=Wolfknives%20Name%20Saved.");
		die();
	} else {
		$msg = "Wolfknives Name was not saved.";
		include("edit.php");
		return;
	}
}
// Delete
elseif ($action=="Delete") {
	$wolfknive = new Wolfknive("");
	$msg = $wolfknive->deleteWolfknive($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);;
	if ($msg == "") {
		header("Location: index.php?msg=Wolfknives%20Name%20Deleted.");
		die();
	} else {
		$msg = "Could not delete Wolfknives Name data.";
		include("edit.php");
		return;
	}
}
// Edit
elseif (!isset($wolfknive) && isset($id)) {
	$wolfknive = new Wolfknive("");
	$wolfknive = $wolfknive->getWolfknive($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
// New
if (!isset($wolfknive)) {
	$wolfknive = new Wolfknive("");
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
?>