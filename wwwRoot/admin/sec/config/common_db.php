<?php
//Connect to DB
function connectNYRDb() {
	$dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$dbConn->set_charset(DB_CHARSET);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	return $dbConn;
}

function handleDbError($theSqlError) {
	die("<p>Failed to connect to database:</p><p><strong>" . $theSqlError . "</strong></p>");
}
?>