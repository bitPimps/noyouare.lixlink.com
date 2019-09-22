<?php
extract(array_merge($_POST,$_GET));
include("../sec/config/phpConfig.php");
include("../sec/config/common_db.php");
include("../sec/phpinclude/classes/WolfknivesMember.php");

$msg = "";

// Connect to Database
$dbConn = connectNYRDb();
// Save
if($action=="Save") {
	$wkMember = new WolfknivesMember("");
	if (isset($publish)) {
		$wkMember->setPublish("1");
	} else {
		$wkMember->setPublish("0");
	}

	if ($firstNameReal == "") {
		$msg = "You can't add someone without a real first name. What is their real first name?";
	} elseif ($nameWK == "") {
		$msg = "You can't add someone without a Wolfknives name. What is their Wolfnives name?";
	}

	$wkMember->setId($id);
	$wkMember->setFirstNameReal(htmlentities($firstNameReal, ENT_QUOTES));
	$wkMember->setLastNameReal(htmlentities($lastNameReal, ENT_QUOTES));
	$wkMember->setNameWK(htmlentities($nameWK, ENT_QUOTES));
	$wkMember->setNameTwitter(htmlentities(str_replace("@", "", $nameTwitter), ENT_QUOTES));
	$wkMember->setNameInstagram(htmlentities(str_replace("@", "", $nameInstagram), ENT_QUOTES));
	$wkMember->setNameOJE(htmlentities($nameOJE, ENT_QUOTES));
	$wkMember->setState(htmlentities($state, ENT_QUOTES));
	$wkMember->setRank(htmlentities($rank, ENT_QUOTES));
	$wkMember->setAccolades(htmlentities($accolades, ENT_QUOTES));
	$wkMember->setPublish($publish);

	if ($msg != "") {
		include("edit.php");
		return;
	}
	$msg = $wkMember->save($dbConn);
	// Close DB Connection
	mysqli_close($dbConn);
	
	if ($msg == "") {
		header("Location: index.php?msg=Wolfknives%20Member%20Saved.");
		die();
	} else {
		$msg = "Wolfknives Member was not saved.";
		include("edit.php");
		return;
	}
}
// Delete
elseif ($action=="Delete") {
	$wkMember = new WolfknivesMember("");
	$msg = $wkMember->deleteWolfknivesMember($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);;

	if ($msg == "") {
		header("Location: index.php?msg=Wolfknives%20Member%20Deleted.");
		die();
	} else {
		$msg = "Could not delete Wolfknives Member data.";
		include("edit.php");
		return;
	}
}
// Edit
elseif (!isset($wkMember) && isset($id)) {
	$wkMember = new WolfknivesMember("");
	$wkMember = $wkMember->getWolfknivesMember($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
// New
if (!isset($wkMember)) {
	$wkMember = new WolfknivesMember("");
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
?>