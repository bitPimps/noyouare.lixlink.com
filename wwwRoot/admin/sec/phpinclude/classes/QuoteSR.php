<?php
class QuoteSR {
	var $quotes;
	var $totalNum;
	var $currNum;
	/* Constructor */
	function __construct ($dbConn, $lowerBound, $upperBound, $author, $sortBy, $publish) {
		// Get total count of quotes
		$sql = "SELECT COUNT(id) FROM Quotes";
		if ($publish) {
			$sql = $sql . " WHERE publish='1'";
		}
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSet = mysqli_fetch_row($recordSetId);
		$this->totalNum = $recordSet[0];

		// Select All Quotes
		$sql = "SELECT id, author, context, body, publish FROM Quotes ";
		if(isset($publish) && $publish != "" && isset($author) && $author != "") {
			$sql = $sql . "WHERE (publish='1' AND author='" . $author . "') ";
		} elseif(isset($publish) && $publish != "" && !isset($author) && $author == "") {
			$sql = $sql . "WHERE (publish='1') ";
		} elseif(!isset($publish) && $publish == "" && isset($author) && $author != "") {
			$sql = $sql . "WHERE (author='" . $author . "') ";
		}

		if(isset($sortBy) && $sortBy != "") {
			$sql = $sql . "ORDER BY " . $sortBy . " DESC, author ASC";
		} else {
			$sql = $sql . "ORDER BY author ASC";
		}
		$sql = $sql . " LIMIT " . $lowerBound . "," . $upperBound;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$this->currNum = mysqli_num_rows($recordSetId);
		while ($recordSet = mysqli_fetch_array($recordSetId)) {
			$this->quotes[] = new Quote($recordSet);
		}
	
	}
	// Getters
	function getCurrNum() {
		return $this->currNum;
	}
	function getTotalNum() {
		return $this->totalNum;
	}
	function getQuotes() {
		return $this->quotes;
	}
}
?>