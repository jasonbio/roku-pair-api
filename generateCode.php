<?php
if ($_GET['deviceid']) {
	$mysqli = new mysqli("localhost", "user", "password", "database");
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$id = $_GET['deviceid'];
	$deviceid_safe = $mysqli->real_escape_string($id);
	$res = $mysqli->query("SELECT * FROM database WHERE deviceid='$deviceid_safe'");
	$row = $res->fetch_assoc();

	if (!$row) {
		$code = generateRandomString();
		$code_safe = $mysqli->real_escape_string($code);
		$expires = strtotime('+10 minutes', time());
		$expires_safe = $mysqli->real_escape_string($expires);
		$status = "waiting";
		$status_safe = $mysqli->real_escape_string($status);
		if ($mysqli->query("INSERT INTO database (code, status, deviceid, expires) VALUES ('$code_safe','$status_safe','$deviceid_safe','$expires_safe')")) {
			header('Content-type: text/xml');
			$output = "<?xml version=\"1.0\"?>\n";
			$output .= "<apiResponse>\n";
				$output .= "<regCode expires=\"".$expires."\">".$code."</regCode>\n";
			$output .= "</apiResponse>";
			echo $output;
		}
	} else {
		$code = generateRandomString();
		$code_safe = $mysqli->real_escape_string($code);
		$expires = strtotime('+10 minutes', time());
		$expires_safe = $mysqli->real_escape_string($expires);
		$status = "waiting";
		$status_safe = $mysqli->real_escape_string($status);
		if ($mysqli->query("INSERT INTO database (code, status, deviceid, expires) VALUES ('$code_safe','$status_safe','$deviceid_safe','$expires_safe')")) {
			header('Content-type: text/xml');
			$output = "<?xml version=\"1.0\"?>\n";
			$output .= "<apiResponse>\n";
				$output .= "<regCode expires=\"".$expires."\">".$code."</regCode>\n";
			$output .= "</apiResponse>";
			echo $output;
		}
	}
}
function generateRandomString($length = 7) {
    return substr(str_shuffle("123456789ABCDEFGHIJKLMNPQRSTUVWXYZ"), 0, $length);
}
?>