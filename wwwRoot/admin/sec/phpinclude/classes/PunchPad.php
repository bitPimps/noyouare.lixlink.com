<?php
class PunchPad {
	var $id;
	var $firstName;
	var $lastName;
	var $strike;
	var $score;
	var $publish;
	function __construct ($recordSetArray) {
		if (!isset($recordSetArray) || $recordSetArray == "") {
			$this->id				= -1;
			$this->firstName		= "";
			$this->lastName			= "";
			$this->strike			= "";
			$this->score			= "";
			$this->publish			= "0";
		} else {
			$this->id				= $recordSetArray["id"];
			$this->firstName		= stripslashes($recordSetArray["firstName"]);
			$this->lastName			= stripslashes($recordSetArray["lastName"]);
			$this->strike			= stripslashes($recordSetArray["strike"]);
			$this->score			= stripslashes($recordSetArray["score"]);
			$this->publish			= stripslashes($recordSetArray["publish"]);
		}
	}

	function save ($dbConn) {
		$sql;
		if (!isset($this->id) || (integer)$this->id == -1 || $this->id == "") {
			$sql =	"INSERT INTO PunchPads" .
					" (firstName, lastName, strike, score, publish)" .
					" VALUES ('" .
							  addslashes($this->firstName) . "','" .
							  addslashes($this->lastName) . "','" .
							  addslashes($this->strike) . "','" .
							  addslashes($this->score) . "','" .
							  addslashes($this->publish) . "')";
		} else {
			$sql = 	"UPDATE PunchPads SET " .
					" firstName='" . addslashes($this->firstName) . "'," .
					" lastName='" . addslashes($this->lastName) . "'," .
					" strike='" . addslashes($this->strike) . "'," .
					" score='" . addslashes($this->score) . "'," .
					" publish='" . addslashes($this->publish) . "'" .
					" WHERE id=" . $this->id;
		}
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
	// Getters / Setters
	function getId() {
		return $this->id;
	}
	function setId($id) {
		$this->id = $id;
	}

	function getFirstName() {
		return $this->firstName;
	}
	function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	function getLastName() {
		return $this->lastName;
	}
	function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	function getStrike() {
		return $this->strike;
	}
	function setStrike($strike) {
		$this->strike = $strike;
	}

	function getScore() {
		return $this->score;
	}
	function setScore($score) {
		$this->score = $score;
	}

	function getPublish() {
		return $this->publish;
	}
	function setPublish($publish) {
		$this->publish = $publish;
	}

	function getPunchPad ($dbConn, $id) {
		// Select All PunchPads
		$sql = "SELECT id, firstName, lastName, strike, score, publish FROM PunchPads WHERE id=" . $id;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSetArray = mysqli_fetch_array($recordSetId);
		$aPunchPad = new PunchPad($recordSetArray);
		return $aPunchPad;
	}

	function deletePunchPad ($dbConn, $id) {
		$sql = "DELETE FROM PunchPads WHERE id=" . $id;
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
}
?>