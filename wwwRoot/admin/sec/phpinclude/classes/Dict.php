<?php
class Dict {
	var $id;
	var $term;
	var $definition;
	var $publish;

	function __construct ($recordSetArray) {
		if (!isset($recordSetArray) || $recordSetArray == "") {
			$this->id 			= -1;
			$this->term 		= "";
			$this->definition 	= "";
			$this->publish 		= "0";
		} else {
			$this->id 			= $recordSetArray["id"];
			$this->term 		= stripslashes($recordSetArray["term"]);
			$this->definition 	= stripslashes($recordSetArray["definition"]);
			$this->publish 		= stripslashes($recordSetArray["publish"]);
		}
	}

	function save ($dbConn) {
		$sql;
		if (!isset($this->id) || (integer)$this->id == -1 || $this->id == "") {
			$sql =	"INSERT INTO Dicts" .
					" (term, definition, publish)"	.
					" VALUES ('" .
							  addslashes($this->term) . "','" .
							  addslashes($this->definition) . "','" .
							  addslashes($this->publish) . "')";
		} else {
			$sql = 	"UPDATE Dicts SET " .
					" term='" . addslashes($this->term) . "'," .
					" definition='" . addslashes($this->definition) . "'," .
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
	// ->	Term
	function getTerm() {
		return $this->term;
	}
	function setTerm($term) {
		$this->term = $term;
	}
	// ->	Definition
	function getDefinition() {
		return $this->definition;
	}
	function setDefinition($definition) {
		$this->definition = $definition;
	}
	// ->	Publish
	function getPublish() {
		return $this->publish;
	}
	function setPublish($publish) {
		$this->publish = $publish;
	}

	function getDict($dbConn, $id) {
		// Select Dict By ID
		$sql = "SELECT id, term, definition, publish FROM Dicts WHERE id=" . $id;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSetArray = mysqli_fetch_array($recordSetId);
		$aDict = new Dict($recordSetArray);
		return $aDict;
	}

	function deleteDict ($dbConn, $id) {
		$sql = "DELETE FROM Dicts WHERE id=" . $id;
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
}
?>