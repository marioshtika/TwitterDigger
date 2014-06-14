<?php
$con = mysqli_connect("host", "username", "password", "database");

// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM tweets3 ORDER BY id DESC LIMIT 1");

$row = mysqli_fetch_array($result);
echo "last record: ".$row['id'] . "<br />" . $row['tweet']."<br />";

?>
