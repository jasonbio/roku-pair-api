<?php
$db = new mysqli("localhost", "user", "pass", "db");
if ($db->connect_errno) {
   printf("Connect failed: %s\n", mysqli_connect_error());
   exit();
}
?>