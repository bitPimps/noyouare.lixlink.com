<?php
extract(array_merge($_POST,$_GET));
include("../sec/config/phpConfig.php");
include("../sec/config/common_db.php");
include("../sec/phpinclude/classes/Dict.php");

$msg = "";

// Connect to Database
$dbConn = connectNYRDb();
// Save
if($action=="Save") {
	$ellisWord = new Dict("");
	if (isset($publish)) {
		$ellisWord->setPublish("1");
	} else {
		$ellisWord->setPublish("0");
	}

	if ($term == "") {
		$msg = "You can't add an entry without a term. What did Ellis say?";
	} elseif ($definition == "") {
		$msg = "You can't add a term without a definition. What did Ellis mean to say?";
	}

	$ellisWord->setId($id);
	$ellisWord->setTerm(htmlentities($term, ENT_QUOTES));
	$ellisWord->setDefinition(htmlentities($definition, ENT_QUOTES));
	$ellisWord->setPublish($publish);

	if ($msg != "") {
		include("edit.php");
		return;
	}
	$msg = $ellisWord->save($dbConn);
	// Close DB Connection
	mysqli_close($dbConn);
	
	if ($msg == "") {
		header("Location: index.php?msg=Term%20Saved.");
		die();
	} else {
		$msg = "Term was not saved.";
		include("edit.php");
		return;
	}
}
// Delete
elseif ($action=="Delete") {
	$ellisWord = new Dict("");
	$msg = $ellisWord->deleteDict($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);;
	if ($msg == "") {
		header("Location: index.php?msg=Term%20Deleted.");
		die();
	} else {
		$msg = "Could not delete Term data.";
		include("edit.php");
		return;
	}
}
// Edit
elseif (!isset($ellisWord) && isset($id)) {
	$ellisWord = new Dict("");
	$ellisWord = $ellisWord->getDict($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
// New
if (!isset($ellisWord)) {
	$ellisWord = new Dict("");
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
?>