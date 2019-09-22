<?php
class Wolfknive {
	var $id;
	var $name;
	var $sex;
	var $publish;

	function __construct ($recordSetArray) {
		if (!isset($recordSetArray) || $recordSetArray == "") {
			$this->id 				= -1;
			$this->name 			= "";
			$this->sex 				= "";
			$this->publish 			= "0";
		} else {
			$this->id 				= $recordSetArray["id"];
			$this->name 			= stripslashes($recordSetArray["name"]);
			$this->sex 				= stripslashes($recordSetArray["sex"]);
			$this->publish 			= stripslashes($recordSetArray["publish"]);
		}
	}

	function save ($dbConn) {
		$sql;
		if (!isset($this->id) || (integer)$this->id == -1 || $this->id == "") {
			$sql =	"INSERT INTO WolfknivesNames" .
					" (name, sex, publish)"	.
					" VALUES ('" .
							  addslashes($this->name) . "','" .
							  addslashes($this->sex) . "','" .
							  addslashes($this->publish) . "')";
		} else {
			$sql = 	"UPDATE WolfknivesNames SET " .
					" name='" . addslashes($this->name) . "'," .
					" sex='" . addslashes($this->sex) . "'," .
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
	// ->	Name
	function getName() {
		return $this->name;
	}
	function setName($name) {
		$this->name = $name;
	}
	// ->	Sex
	function getSex() {
		return $this->sex;
	}
	function setSex($sex) {
		$this->sex = $sex;
	}
	// ->	Publish
	function getPublish() {
		return $this->publish;
	}
	function setPublish($publish) {
		$this->publish = $publish;
	}

	function getWolfknive ($dbConn, $id) {
		// Select Wolfknive By ID
		$sql = "SELECT id, name, sex, publish FROM WolfknivesNames WHERE id=" . $id;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSetArray = mysqli_fetch_array($recordSetId);
		$aWolfknive = new Wolfknive($recordSetArray);
		return $aWolfknive;
	}

	function deleteWolfknive ($dbConn, $id) {
		$sql = "DELETE FROM WolfknivesNames WHERE id=" . $id;
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
}
?>