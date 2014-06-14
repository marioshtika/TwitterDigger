<?php

// show all warning and errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// the maximum execution time, in seconds. If set to zero, no time limit is imposed.
set_time_limit(0);

// echo 'start procedure<br />';

// connect to remote db
$con = mysqli_connect("host", "username", "password", "database");

// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// echo 'connected to remote db<br />';


// connect to mongo db
$m = new MongoClient();

// select a database
$db = $m->twitter_db;

// select a collection (analogous to a relational database's table)
$collection = $db->TwitterDigger;

// echo 'connected to mongo db<br />';
$start = $_GET['page'];
$total = 1000;

$result = mysqli_query($con,"SELECT * FROM tweets3 ORDER BY id ASC LIMIT ".$start.", ".$total) or die(mysql_error());
while ($row = mysqli_fetch_array($result)) {
	// echo $row['id'].': ';
	$string = $row['tweet'];

	// echo 'retrieved tweet from db<br />';


	// fixed tweet from special charaters
	$string = preg_replace("/source(.*?)truncated/", "source\":\"\", \"truncated", $string);

	// echo 'cleaned up tweet<br />';

	// Convert JSON to a PHP array
	$document = json_decode($string);
	
	// echo 'decoded tweet from json<br />';

	if ($document != NULL) {
		// add a record
		$collection->insert($document);

		// echo 'save twitter to mongodb<br />';

	} else {
		// echo 'twitter was corrupted<br />';
	}

	// echo '-----------------------------------------------------------<br />';
}

// find everything in the collection
$cursor = $collection->find();

// echo '-----------------------------------------------------------<br />';
// iterate through the results
foreach ($cursor as $document) {
	/*
	echo '<pre>';
	var_dump($document);
	echo '</pre>';
	*/
	// echo $document["text"] . '<br />';
}

$page = $start + $total;
header("Location: download-tweet-to-mongodb.php?page=".$page);


?>
