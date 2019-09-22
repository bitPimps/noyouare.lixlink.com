<?php
extract(array_merge($_POST,$_GET));
include("../sec/config/phpConfig.php");
include("../sec/config/common_db.php");
include("../sec/phpinclude/classes/Quote.php");

$msg = "";

// Connect to Database
$dbConn = connectNYRDb();
// Save
if($action=="Save") {
	$quote = new Quote("");
	if (isset($publish)) {
		$quote->setPublish("1");
	} else {
		$quote->setPublish("0");
	}

	if ($author == "") {
		$msg = "Select a person to quote.";
	} elseif ($body == "")
	{
		$msg = "What's the fuckin' quote, dude?";
	} elseif ($context == "") {
		$msg = "What's the context, man?";
	}

	$quote->setId($id);
	$quote->setAuthor($author);
	$quote->setBody(htmlentities($body, ENT_QUOTES));
	$quote->setContext(htmlentities($context, ENT_QUOTES));
	$quote->setPublish($publish);

	if ($msg != "") {
		include("edit.php");
		return;
	}
	$msg = $quote->save($dbConn);
	// Close DB Connection
	mysqli_close($dbConn);
	
	if ($msg == "") {
		header("Location: index.php?msg=Quote%20Saved.");
		die();
	} else {
		$msg = "Quote was not saved.";
		include("edit.php");
		return;
	}
}
// Delete
elseif ($action=="Delete") {
	$quote = new Quote("");
	$msg = $quote->deleteQuote($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);;
	if ($msg == "") {
		header("Location: index.php?msg=Quote%20Deleted.");
		die();
	} else {
		$msg = "Could not delete quote data.";
		include("edit.php");
		return;
	}
}
// Edit
elseif (!isset($quote) && isset($id)) {
	$quote = new Quote("");
	$quote = $quote->getQuote($dbConn, $id);
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
// New
if (!isset($quote)) {
	$quote = new Quote("");
	// Close DB Connection
	mysqli_close($dbConn);
	include("edit.php");
}
?>