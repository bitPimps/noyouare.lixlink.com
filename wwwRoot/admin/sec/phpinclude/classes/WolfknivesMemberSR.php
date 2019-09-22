<?php
class WolfknivesMemberSR {
	var $wolfknivesMembers;
	var $totalNum;
	var $currNum;
	/* Constructor */
	function __construct ($dbConn, $lowerBound, $upperBound, $searchCrit, $sortBy, $publish) {
		// Get total count of guests
		$sql = "SELECT COUNT(id) FROM WolfknivesMembers";
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

		// Select All WolfknivesMembers
		$sql = "SELECT id, firstNameReal, lastNameReal, nameWK, nameTwitter, nameInstagram, nameOJE, state, rank, accolades, publish FROM WolfknivesMembers ";
		if(isset($publish) && $publish != "" && isset($searchCrit) && $searchCrit != "") {
			$sql = $sql . "WHERE (publish='1' AND nameWK LIKE '%" . $searchCrit . "%') ";
		} elseif(isset($publish) && $publish != "" && !isset($searchCrit) && $searchCrit == "") {
			$sql = $sql . "WHERE (publish='1') ";
		} elseif(!isset($publish) && $publish == "" && isset($searchCrit) && $searchCrit != "") {
			$sql = $sql . "WHERE (nameWK LIKE '%" . $searchCrit . "%') ";
		}

		if(isset($sortBy) && $sortBy != "") {
			$sql = $sql . "ORDER BY " . $sortBy . " ASC, state ASC";
		} else {
			$sql = $sql . "ORDER BY nameWK ASC, state ASC";
		}
		$sql = $sql . " LIMIT " . $lowerBound . "," . $upperBound;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$this->currNum = mysqli_num_rows($recordSetId);
		while ($recordSet = mysqli_fetch_array($recordSetId)) {
			$this->wolfknivesMembers[] = new WolfknivesMember($recordSet);
		}
	}
	// Getters
	function getCurrNum() {
		return $this->currNum;
	}
	function getTotalNum() {
		return $this->totalNum;
	}
	function getWolfknivesMembers() {
		return $this->wolfknivesMembers;
	}
}
?>