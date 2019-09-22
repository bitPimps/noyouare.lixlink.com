<?php
//  No term passed - just exit early with no response
if (empty($_GET['term'])) exit;
include_once("../sec/config/phpConfig.php");
include_once("../sec/config/common_db.php");

// Connect to Database
$dbConn = connectNYRDb();
$items = array();
$res = mysqli_query($dbConn, "SELECT name FROM Guests WHERE (name LIKE '%" . trim(strip_tags($_GET['term'])) . "%') ORDER BY name ASC");
while($row = mysqli_fetch_array($res)) {
	$items[] = html_entity_decode($row['name'], ENT_QUOTES, "UTF-8");
}
//echo $items;
// Close DB Connection
mysqli_close($dbConn);
// JSON encode results
echo json_encode($items);

//Select All Cities in Addresses table
/*
$res = odbc_exec($dbConn, "SELECT DISTINCT city FROM Addresses WHERE (city <> '') AND (city LIKE '%" . trim(strip_tags($_GET['term'])) . "%') ORDER BY city ASC");
while(odbc_fetch_row($res))
{
	$items[] = odbc_result($res, "city");
}
echo json_encode($items);
*/
?>