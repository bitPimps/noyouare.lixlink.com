<?php
class PunchPadSR {
	var $punchpads;
	var $totalNum;
	var $currNum;
	/* Constructor */
	function __construct ($dbConn, $lowerBound, $upperBound, $searchCrit, $strike, $sortBy, $publish) {
		// Get total count of quotes
		$sql = "SELECT COUNT(id) FROM PunchPads";
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

		// Select All PunchPads
		$sql = "SELECT id, firstName, lastName, strike, score, publish FROM PunchPads ";
		if(isset($publish) && $publish != "" && !isset($searchCrit) && $searchCrit == "" && !isset($strike) && $strike == "") {
			$sql = $sql . "WHERE (publish='1') ";
		} elseif(isset($publish) && $publish != "" && isset($searchCrit) && $searchCrit != "" && !isset($strike) && $strike == "") {
			$sql = $sql . "WHERE publish='1' AND (firstName LIKE '%" . $searchCrit . "%' OR lastName LIKE '%" . $searchCrit . "%') ";
		} elseif(isset($publish) && $publish != "" && !isset($searchCrit) && $searchCrit == "" && isset($strike) && $strike != "") {
			$sql = $sql . "WHERE (publish='1' AND strike='" . $strike . "') ";
		} elseif(isset($publish) && $publish != "" && isset($searchCrit) && $searchCrit != "" && isset($strike) && $strike != "") {
			$sql = $sql . "WHERE publish='1' AND strike='". $strike . "' AND (firstName LIKE '%" . $searchCrit . "%' OR lastName LIKE '%" . $searchCrit . "%') ";
		} elseif(!isset($publish) && $publish == "" && isset($searchCrit) && $searchCrit != "" && !isset($strike) && $strike == "") {
			$sql = $sql . "WHERE (firstName LIKE '%" . $searchCrit . "%' OR lastName LIKE '%" . $searchCrit . "%') ";
		} elseif(!isset($publish) && $publish == "" && !isset($searchCrit) && $searchCrit == "" && isset($strike) && $strike != "") {
			$sql = $sql . "WHERE (strike='" . $strike . "') ";
		} elseif(!isset($publish) && $publish == "" && isset($searchCrit) && $searchCrit != "" && isset($strike) && $strike != "") {
			$sql = $sql . "WHERE strike='" . $strike . "' AND (firstName LIKE '%" . $searchCrit . "%' OR lastName LIKE '%" . $searchCrit . "%') ";
		}
		
		if(isset($sortBy) && $sortBy != "") {
			$sql = $sql . "ORDER BY " . $sortBy . " ASC";
		} else {
			$sql = $sql . "ORDER BY score DESC, firstName ASC";
		}
		$sql = $sql . " LIMIT " . $lowerBound . "," . $upperBound;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$this->currNum = mysqli_num_rows($recordSetId);
		while ($recordSet = mysqli_fetch_array($recordSetId)) {
			$this->punchpads[] = new PunchPad($recordSet);
		}
	
	}
	// Getters
	function getCurrNum() {
		return $this->currNum;
	}
	function getTotalNum() {
		return $this->totalNum;
	}
	function getPunchPads() {
		return $this->punchpads;
	}
}
?>