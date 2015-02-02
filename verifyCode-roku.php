<?php
if ($_GET['deviceid']) {
	$mysqli = new mysqli("localhost", "user", "password", "database");
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$id = $_GET['deviceid'];
 	$deviceid_safe = $mysqli->real_escape_string($id);
	$status = "valid";
	$status_safe = $mysqli->real_escape_string($status);
	$res = $mysqli->query("SELECT * FROM database WHERE deviceid='$deviceid_safe' AND status='$status_safe'");
	$row = $res->fetch_assoc();

	if ($row) {
		$status2 = "paired";
		$status_safe2 = $mysqli->real_escape_string($status2);
		if ($mysqli->query("UPDATE database SET status='$status_safe2', code = null, expires = null WHERE deviceid='$deviceid_safe' AND status='$status_safe')")) {
			header('Content-type: text/xml');
			$output = "<?xml version=\"1.0\"?>\n";
			$output .= "<apiResponse>\n";
			$output .= "<status>paired</status>\n";
			$output .= "</apiResponse>";
			echo $output;
		} else {
			header('Content-type: text/xml');
			$output = "<?xml version=\"1.0\"?>\n";
			$output .= "<apiResponse>\n";
			$output .= "<status>failure</status>\n";
			$output .= "</apiResponse>";
			echo $output;
		}
	} else {
		header('Content-type: text/xml');
		$output = "<?xml version=\"1.0\"?>\n";
		$output .= "<apiResponse>\n";
		$output .= "<status>failure</status>\n";
		$output .= "</apiResponse>";
		echo $output;
	}
} else {
	header('Content-type: text/xml');
	$output = "<?xml version=\"1.0\"?>\n";
	$output .= "<apiResponse>\n";
	$output .= "<status>failure</status>\n";
	$output .= "</apiResponse>";
	echo $output;
}
?>