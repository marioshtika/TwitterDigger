<h1>Burstiness</h1>
<hr />

<?php
if(!isset($_GET['keyword'])) {
	// connect to mongo db
	$m = new MongoClient();

	// select a database
	$db = $m->twitter_db;

	// select a collection (analogous to a relational database's table)
	$collection = $db->TwitterDigger;

	// select a collection (analogous to a relational database's table)
	$termCollection = $db->TermCollection;

	// show terms frequency
	$limitRow = 10;
	echo 'Show <strong>'.$limitRow.'</strong> most frequently terms used.<br /><br />';

	$terms = $termCollection->find()->limit(10);
	$terms = $terms->sort(array('frequency' => -1));

	foreach ($terms as $term) {
		echo $term['term'].': ('.$term['frequency'].' times) ';
		echo '<a href="index.php?route=burstiness&keyword='.$term['term'].'">Create Burstiness</a><br />';
	}
} else {
	include('burstiness.php');
}

?>