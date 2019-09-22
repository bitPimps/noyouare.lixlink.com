<?php
class WolfknivesMember {
	var $id;
	var $firstNameReal;
	var $lastNameReal;
	var $nameWK;
	var $nameTwitter;
	var $nameInstagram;
	var $nameOJE;
	var $state;
	var $rank;
	var $accolades;
	var $publish;

	function __construct ($recordSetArray) {
		if (!isset($recordSetArray) || $recordSetArray == "") {
			$this->id				= -1;
			$this->firstNameReal	= "";
			$this->lastNameReal		= "";
			$this->nameWK			= "";
			$this->nameTwitter		= "";
			$this->nameInstagram	= "";
			$this->nameOJE			= "";
			$this->state			= "";
			$this->rank				= "";
			$this->accolades		= "";
			$this->publish			= "0";
		} else {
			$this->id				= $recordSetArray["id"];
			$this->firstNameReal	= stripslashes($recordSetArray["firstNameReal"]);
			$this->lastNameReal		= stripslashes($recordSetArray["lastNameReal"]);
			$this->nameWK			= stripslashes($recordSetArray["nameWK"]);
			$this->nameTwitter		= stripslashes($recordSetArray["nameTwitter"]);
			$this->nameInstagram	= stripslashes($recordSetArray["nameInstagram"]);
			$this->nameOJE			= stripslashes($recordSetArray["nameOJE"]);
			$this->state			= stripslashes($recordSetArray["state"]);
			$this->rank				= stripslashes($recordSetArray["rank"]);
			$this->accolades		= stripslashes($recordSetArray["accolades"]);
			$this->publish			= stripslashes($recordSetArray["publish"]);
		}
	}

	function save ($dbConn) {
		$sql;
		if (!isset($this->id) || (integer)$this->id == -1 || $this->id == "") {
			$sql =	"INSERT INTO WolfknivesMembers" .
					" (firstNameReal, lastNameReal, nameWK, nameTwitter, nameInstagram, nameOJE, state, rank, accolades, publish)" .
					" VALUES ('" .
							  addslashes($this->firstNameReal) . "','" .
							  addslashes($this->lastNameReal) . "','" .
							  addslashes($this->nameWK) . "','" .
							  addslashes($this->nameTwitter) . "','" .
							  addslashes($this->nameInstagram) . "','" .
							  addslashes($this->nameOJE) . "','" .
							  addslashes($this->state) . "','" .
							  addslashes($this->rank) . "','" .
							  addslashes($this->accolades) . "','" .
							  addslashes($this->publish) . "')";
		} else {
			$sql = 	"UPDATE WolfknivesMembers SET " .
					" firstNameReal='" . addslashes($this->firstNameReal) . "'," .
					" lastNameReal='" . addslashes($this->lastNameReal) . "'," .
					" nameWK='" . addslashes($this->nameWK) . "'," .
					" nameTwitter='" . addslashes($this->nameTwitter) . "'," .
					" nameInstagram='" . addslashes($this->nameInstagram) . "'," .
					" nameOJE='" . addslashes($this->nameOJE) . "'," .
					" state='" . addslashes($this->state) . "'," .
					" rank='" . addslashes($this->rank) . "'," .
					" accolades='" . addslashes($this->accolades) . "'," .
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
	// ->	ID
	function getId() {
		return $this->id;
	}
	function setId($id) {
		$this->id = $id;
	}
	// ->	FirstNameReal
	function getFirstNameReal() {
		return $this->firstNameReal;
	}
	function setFirstNameReal($firstNameReal) {
		$this->firstNameReal = $firstNameReal;
	}
	// ->	LastNameReal
	function getLastNameReal() {
		return $this->lastNameReal;
	}
	function setLastNameReal($lastNameReal) {
		$this->lastNameReal = $lastNameReal;
	}
	// ->	NameWK
	function getNameWK() {
		return $this->nameWK;
	}
	function setNameWK($nameWK) {
		$this->nameWK = $nameWK;
	}
	// ->	NameTwitter
	function getNameTwitter() {
		return $this->nameTwitter;
	}
	function setNameTwitter($nameTwitter) {
		$this->nameTwitter = $nameTwitter;
	}
	// ->	NameInstagram
	function getNameInstagram() {
		return $this->nameInstagram;
	}
	function setNameInstagram($nameInstagram) {
		$this->nameInstagram = $nameInstagram;
	}
	// ->	NameOJE
	function getNameOJE() {
		return $this->nameOJE;
	}
	function setNameOJE($nameOJE) {
		$this->nameOJE = $nameOJE;
	}
	// ->	State
	function getState() {
		return $this->state;
	}
	function setState($state) {
		$this->state = $state;
	}
	// ->	Rank
	function getRank() {
		return $this->rank;
	}
	function setRank($rank) {
		$this->rank = $rank;
	}
	// ->	Accolades
	function getAccolades() {
		return $this->accolades;
	}
	function setAccolades($accolades) {
		$this->accolades = $accolades;
	}
	// ->	Publish
	function getPublish() {
		return $this->publish;
	}
	function setPublish($publish) {
		$this->publish = $publish;
	}

	function getWolfknivesMember($dbConn, $id) {
		// Select WolfknivesMember By ID
		$sql = "SELECT id, firstNameReal, lastNameReal, nameWK, nameTwitter, nameInstagram, nameOJE, state, rank, accolades, publish FROM WolfknivesMembers WHERE id=" . $id;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSetArray = mysqli_fetch_array($recordSetId);
		$aWolfknivesMember = new WolfknivesMember($recordSetArray);
		return $aWolfknivesMember;
	}

	function deleteWolfknivesMember ($dbConn, $id) {
		$sql = "DELETE FROM WolfknivesMembers WHERE id=" . $id;
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
	// Prepare Input for DB Entry
	function encode($input) {
		$input = html_entity_decode ($input, ENT_QUOTES);
		return $input;
	}
}
?>