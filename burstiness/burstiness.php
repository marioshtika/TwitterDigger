<?php
  
// connect to mongo db
$m = new MongoClient();

// select a database
$db = $m->twitter_db;

// select a collection (analogous to a relational database's table)
$collection = $db->TwitterDigger;

echo "Prepare Burstiness table: <strong>ok</strong><br />";

echo '<hr />';

// select a collection (analogous to a relational database's table)
$burstinessCollection = $db->BurstinessCollection;

// clean table (remove all documents)
$burstinessCollection->remove();
		
// count rows in the collection
$allTweets = $collection->count();
echo 'All tweets: <strong>'.$allTweets.'</strong><br />';

// count english rows in the collection
$tweetsInEnglish = $collection->count(array('lang'=>'en'));
echo 'Tweets in english: <strong>'.$tweetsInEnglish.'</strong><br />';

// count rows in the collection

$keywordTweets = $collection->count(array('lang'=>'en', 'text' => new MongoRegex('/'.$_GET['keyword'].'/')));
echo 'All tweets with the keyword '.$_GET['keyword'].': <strong>'.$keywordTweets.'</strong><br />';

// find everything in english from the collection
$documents = $collection->find(array('lang'=>'en', 'text' => new MongoRegex('/'.$_GET['keyword'].'/')));

// iterate through the results
foreach ($documents as $tweet) {
	$burstinessCollection->insert(array(
		'time' => strtotime($tweet['created_at']),
		'tweet' => $tweet['text']
	));
}
	

echo '<hr />';

echo 'Show chart: Burstines of <strong>'.$_GET['keyword'].'</strong>';

?>
