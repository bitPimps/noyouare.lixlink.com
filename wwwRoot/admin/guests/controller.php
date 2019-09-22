<?php
extract(array_merge($_POST,$_GET));
include("../sec/config/phpConfig.php");
include("../sec/config/common_db.php");
include("../sec/phpinclude/classes/Guest.php");

$msg = "";

// Connect to Database
$dbConn = connectNYRDb();
// Save
if($action=="Save") {
	$guest = new Guest("");
	if (isset($publish)) {
		$guest->setPublish("1");
	} else {
		$guest->setPublish("0");
	}

	if ($name == "") {
		$msg = "Who was the guest?";
	} elseif ($url == "") {
		$msg = "No Twitter? There must be a link for them, right?";
	} elseif ($body == "") {
		$msg = "Well, how'd they do, good, bad?";
	}

	$guest->setId($id);
	$guest->setName(htmlentities($name, ENT_QUOTES));
	$guest->setUrl(htmlentities($url, ENT_QUOTES));
	$guest->setBody(htmlentities($body, ENT_QUOTES));
	$guest->setPublish($publish);

	if ($msg != "") {
		include("edit.php");
		return;
	}
	$msg = $guest->save($dbConn);
	// Close DB Connection
	mysqli_close($dbConn);
	
	if ($msg == "") {
		header("Location: index.php?msg=Guest%20Saved.");
		die();
	} else {
		$msg = "Guest was not saved.";
		include("edit.php");
		return;
	}
}
// Delete
elseif ($action=="Delete") {
	$guest = new Guest("");
	$msg = $guest->deleteGuest($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);;
	if ($msg == "") {
		header("Location: index.php?msg=Guest%20Deleted.");
		die();
	} else {
		$msg = "Could not delete guest data.";
		include("edit.php");
		return;
	}
}
// Edit
elseif (!isset($guest) && isset($id)) {
	$guest = new Guest("");
	$guest = $guest->getGuest($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
// New
if (!isset($guest)) {
	$guest = new Guest("");
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
?>