<?php
  
// connect to mongo db
$m = new MongoClient();

// select a database
$db = $m->twitter_db;

// select a collection (analogous to a relational database's table)
$collection = $db->TwitterDigger;

// select a collection (analogous to a relational database's table)
$burstinessCollection = $db->BurstinessCollection;
		
if(isset($_GET['func'])) {
	if($_GET['func'] == 'run') {
		echo "Prepare Burstiness table: <strong>ok</strong><br />";

		echo '<hr />';
		
		// clean table (remove all documents)
		$burstinessCollection->remove();
		
		// find everything in english from the collection
		$documents = $collection->find(array('lang'=>'en', 'text' => new MongoRegex('/'.$_GET['keyword'].'/')));

		// iterate through the results
		foreach ($documents as $tweet) {
			$burstinessCollection->insert(array(
				'time' => strtotime($tweet['created_at']),
			));
		}
	}
} else {
	echo '<a class="btn btn-success" href="index.php?route=burstiness&keyword='.$_GET['keyword'].'&func=run" role="button">Extract Burstiness</a>';
	echo '<hr />';
}

// count rows in the collection
$allTweets = $collection->count();
echo 'All tweets: <strong>'.$allTweets.'</strong><br />';

// count english rows in the collection
$tweetsInEnglish = $collection->count(array('lang'=>'en'));
echo 'Tweets in english: <strong>'.$tweetsInEnglish.'</strong><br />';

// count rows in the collection
$keywordTweets = $collection->count(array('lang'=>'en', 'text' => new MongoRegex('/'.$_GET['keyword'].'/')));
echo 'All tweets with the keyword '.$_GET['keyword'].': <strong>'.$keywordTweets.'</strong><br />';

echo 'Show chart: Burstines of <strong>'.$_GET['keyword'].'</strong>';

// create array with time and burstiness
$terms = $burstinessCollection->find();
$terms = $terms->sort(array('time' => 1));

$time = 0;
$timeFrequency = 0;

foreach ($terms as $term) {
	//echo 'term time: '.$term['time'].'<br />';
	//echo 'time: '.$time.'<br /><br />';
	//echo 'frequency: '.$timeFrequency.'<br /><br />';
	if($time != $term['time']) {
		echo $term['time'].': '.$timeFrequency.' times<br />';
		$time = $term['time'];
		$timeFrequency = 1;
	} else {
		$timeFrequency++;
	}
	//echo $term['time'].'<br />';
}

?>
