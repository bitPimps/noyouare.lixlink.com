<?php
class WolfkniveSR {
	var $wolfknives;
	var $totalNum;
	var $currNum;
	/* Constructor */
	function __construct ($dbConn, $lowerBound, $upperBound, $name, $sortBy, $publish) {
		// Get total count of guests
		$sql = 	"SELECT COUNT(id) FROM WolfknivesNames";
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

		// Select All Wolfknives
		$sql = "SELECT id, name, sex, publish FROM WolfknivesNames ";
		if(isset($publish) && $publish != "" && isset($name) && $name != "") {
			$sql = $sql . "WHERE (publish='1' AND name='" . $name . "') ";
		} elseif(isset($publish) && $publish != "" && !isset($name) && $name == "") {
			$sql = $sql . "WHERE (publish='1') ";
		} elseif(!isset($publish) && $publish == "" && isset($name) && $name != "") {
			$sql = $sql . "WHERE (name='" . $guest . "') ";
		}

		if(isset($sortBy) && $sortBy != "") {
			$sql = $sql . "ORDER BY " . $sortBy . " DESC, name ASC";
		} else {
			$sql = $sql . "ORDER BY name ASC";
		}
		$sql = $sql . " LIMIT " . $lowerBound . "," . $upperBound;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$this->currNum = mysqli_num_rows($recordSetId);
		while ($recordSet = mysqli_fetch_array($recordSetId)) {
			$this->wolfknives[] = new Wolfknive($recordSet);
		}
	}
	// Getters
	function getCurrNum() {
		return $this->currNum;
	}
	function getTotalNum() {
		return $this->totalNum;
	}
	function getWolfknives() {
		return $this->wolfknives;
	}
}
?>