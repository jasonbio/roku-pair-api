<?php
include("includes/db.php");
$numrows = 0;

if ($_GET['deviceid']) {
	$id_safe = "'" . $db->real_escape_string($_GET['deviceid']) . "'";
	$paired = "paired";
	$query = $db->query("SELECT * FROM table WHERE deviceid=? AND status=?");
	$query->bind_param('ss', $id_safe, $paired); // Roku device IDs are alphanumeric
    $query->execute();
    $query->store_result();
    $numrows = $query->num_rows;
    $query->free_result();

	if ($numrows == 0) {
		$code = generateRandomString();
		$code_safe = "'" . $db->real_escape_string($code) . "'";
		$expires = strtotime('+10 minutes', time());
		$expires_safe = "'" . $db->real_escape_string($expires) . "'";
		$status = "waiting";
		$status_safe = "'" . $db->real_escape_string($status) . "'";
		if ($db->query("INSERT INTO table (code, status, deviceid, expires) VALUES ($code_safe,$status_safe,$id_safe,$expires_safe)")) {
			header('Content-type: text/xml');
			$output = "<?xml version=\"1.0\"?>\n";
			$output .= "<apiResponse>\n";
			$output .= "<regCode expires=\"".$expires."\">".$code."</regCode>\n";
			$output .= "</apiResponse>";
			echo $output;
		}
	} else {
		header('Content-type: text/xml');
		$output = "<?xml version=\"1.0\"?>\n";
		$output .= "<apiResponse>\n";
		$output .= "Device already paired\n";
		$output .= "</apiResponse>";
		echo $output;
	}
}
function generateRandomString($length = 7) {
    return substr(str_shuffle("123456789ABCDEFGHIJKLMNPQRSTUVWXYZ"), 0, $length);
}
?>