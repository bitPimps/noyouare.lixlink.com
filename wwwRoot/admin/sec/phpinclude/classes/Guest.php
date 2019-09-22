<?php
class Guest {
	var $id;
	var $name;
	var $url;
	var $body;
	var $publish;

	function __construct ($recordSetArray) {
		if (!isset($recordSetArray) || $recordSetArray == "") {
			$this->id 					= -1;
			$this->name 				= "";
			$this->url 					= "";
			$this->body 				= "";
			$this->publish 				= "0";
		} else {
			$this->id 					= $recordSetArray["id"];
			$this->name 				= stripslashes($recordSetArray["name"]);
			$this->url 					= stripslashes($recordSetArray["url"]);
			$this->body 				= stripslashes($recordSetArray["body"]);
			$this->publish 				= stripslashes($recordSetArray["publish"]);
		}
	}

	function save ($dbConn) {
		$sql;
		if (!isset($this->id) || (integer)$this->id == -1 || $this->id == "") {
			$sql =	"INSERT INTO Guests" .
					" (name, url, body, publish)" .
					" VALUES ('" .
							  addslashes($this->name) . "','" .
							  addslashes($this->url) . "','" .
							  addslashes($this->body) . "','" .
							  addslashes($this->publish) . "')";
		} else {
			$sql = 	"UPDATE Guests SET " .
					" name='" . addslashes($this->name) . "'," .
					" url='" . addslashes($this->url) . "'," .
					" body='" . addslashes($this->body) . "'," .
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
	// ->	URL
	function getUrl() {
		return $this->url;
	}
	function setUrl($url) {
		$this->url = $url;
	}
	// ->	Body
	function getBody() {
		return $this->body;
	}
	function setBody($body) {
		$this->body = $body;
	}
	// ->	Publish
	function getPublish() {
		return $this->publish;
	}
	function setPublish($publish) {
		$this->publish = $publish;
	}

	function getGuest ($dbConn, $id) {
		// Select Guest By ID
		$sql = "SELECT id, name, url, body, publish FROM Guests WHERE id=" . $id;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSetArray = mysqli_fetch_array($recordSetId);
		$aGuest = new Guest($recordSetArray);
		return $aGuest;
	}

	function deleteGuest ($dbConn, $id) {
		$sql = "DELETE FROM Guests WHERE id=" . $id;
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
}
?>