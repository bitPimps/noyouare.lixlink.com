<?php
class Quote {
	var $id;
	var $author;
	var $context;
	var $body;
	var $publish;

	function __construct ($recordSetArray) {
		if (!isset($recordSetArray) || $recordSetArray == "") {
			$this->id 				= -1;
			$this->author 			= "";
			$this->context 			= "";
			$this->body 			= "";
			$this->publish 			= "0";
		} else {
			$this->id 				= $recordSetArray["id"];
			$this->author 			= stripslashes($recordSetArray["author"]);
			$this->context 			= stripslashes($recordSetArray["context"]);
			$this->body 			= stripslashes($recordSetArray["body"]);
			$this->publish 			= stripslashes($recordSetArray["publish"]);
		}
	}

	function save ($dbConn) {
		$sql;
		if (!isset($this->id) || (integer)$this->id == -1 || $this->id == "") {
			$sql =	"INSERT INTO Quotes" .
					" (author, context, body, publish)"	.
					" VALUES ('" .
							  addslashes($this->author) . "','" .
							  addslashes($this->context) . "','" .
							  addslashes($this->body) . "','" .
							  addslashes($this->publish) . "')";
		} else {
			$sql = 	"UPDATE Quotes SET " .
					" author='" . addslashes($this->author) . "'," .
					" context='" . addslashes($this->context) . "'," .
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
	function getId() {
		return $this->id;
	}
	function setId($id) {
		$this->id = $id;
	}

	function getAuthor() {
		return $this->author;
	}
	function setAuthor($author) {
		$this->author = $author;
	}

	function getContext() {
		return $this->context;
	}
	function setContext($context) {
		$this->context = $context;
	}

	function getBody() {
		return $this->body;
	}
	function setBody($body) {
		$this->body = $body;
	}

	function getPublish() {
		return $this->publish;
	}
	function setPublish($publish) {
		$this->publish = $publish;
	}

	function getQuote($dbConn, $id) {
		// Select All Quotes
		$sql = "SELECT id, author, context, body, publish FROM Quotes WHERE id=" . $id;
		$recordSetId = mysqli_query($dbConn, $sql);
		if(!$recordSetId) {
			$theSqlError = mysqli_error($dbConn);
			handleDbError($theSqlError);
		}
		$recordSetArray = mysqli_fetch_array($recordSetId);
		$aQuote = new Quote($recordSetArray);
		return $aQuote;
	}

	function deleteQuote ($dbConn, $id) {
		$sql = "DELETE FROM Quotes WHERE id=" . $id;
		$result = mysqli_query($dbConn, $sql);
		if (!$result) {
			return "Database Error Occurred: " . htmlspecialchars(mysqli_error());
		} else {
			return "";
		}
	}
}
?>