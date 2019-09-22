<?php
class DictSR {
	var $terms;
	var $totalNum;
	var $currNum;
	/* Constructor */
	function __construct ($dbConn, $lowerBound, $upperBound, $term, $sortBy, $publish) {
		// Get total count of terms
		$sql = "SELECT COUNT(id) FROM Dicts";
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

		// Select All Dicts
		$sql = "SELECT id, term, definition, publish FROM Dicts ";
		if (isset($publish) && $publish != "" && isset($term) && $term != "") {
			$sql = $sql . "WHERE (publish='1' AND term LIKE '%" . $term . "%') ";
		} elseif (isset($publish) && $publish != "" && !isset($term) && $term == "") {
			$sql = $sql . "WHERE (publish='1') ";
		} elseif(!isset($publish) && $publish == "" && isset($term) && $term != "") {
			$sql = $sql . "WHERE (term LIKE '%" . $term . "%') ";
		}

		if(isset($sortBy) && $sortBy != "") {
			$sql = $sql . "ORDER BY " . $sortBy . " DESC, term ASC";
		} else {
			$sql = $sql . "ORDER BY term ASC";
		}
		$sql = $sql . " LIMIT " . $lowerBound . "," . $upperBound;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$this->currNum = mysqli_num_rows($recordSetId);
		while ($recordSet = mysqli_fetch_array($recordSetId)) {
			$this->terms[] = new Dict($recordSet);
		}
	}
	// Getters
	function getCurrNum() {
		return $this->currNum;
	}
	function getTotalNum() {
		return $this->totalNum;
	}
	function getDicts() {
		return $this->terms;
	}
}
?>