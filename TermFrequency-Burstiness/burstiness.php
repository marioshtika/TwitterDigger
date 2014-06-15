<?php
// show all warning and errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// the maximum execution time, in seconds. If set to zero, no time limit is imposed.
set_time_limit(0);

require 'preprocessor.php';

// for tweet preprocessing
$newTweet = new preprocessor();
  
// connect to mongo db
$m = new MongoClient();

// select a database
$db = $m->twitter_db;

// select a collection (analogous to a relational database's table)
$collection = $db->TwitterDigger;

// select a collection (analogous to a relational database's table)
$termCollection = $db->TermBurstiness;

// find everything in the collection
$documents = $collection->find();

// differenct statistics variables
$allTweets = 0;
$tweetsInEnglish = 0;

// iterate through the results
foreach ($documents as $tweet) {
	// count all tweets
	$allTweets++;

	// remove of non english tweets
	if($tweet['lang'] == 'en') {
		// count tweets in english
		$tweetsInEnglish++;
		
		// 1st process
		// remove all url from tweet if exists
		$cleanTweet = $newTweet->removeUrlFromTweet($tweet['entities']['urls'], $tweet['text']);
		
		// 2nd process
		// remove common words and twitter slang words
		$cleanTweet = $newTweet->removeCommonWords($cleanTweet);
		
		// 3rd process
		// remove special chars
		$cleanTweet = $newTweet->clean($cleanTweet);
		
		//echo 'Dirty tweet: '.$tweet['text'].'<br />';
		//echo 'Clean tweet: '.$cleanTweet.'<br />';
		
		$tokens = explode(" ", $cleanTweet);
		
		foreach ($tokens as $term) {
			if(($term != '') && ($term != '-')) {
				// check only for the most frequently terms
				// if() {
				$termCollection->insert(array(strtotime($tweet['created_at']) => $term));
				// }
			}
		}
	}
	
	/*
		if($allTweets == 200) {
			break;
		}
	*/
}


// Different Statistics
echo '<strong>Different Statistics</strong><br /><br />';
echo 'All tweets: <strong>'.$allTweets.'</strong><br />';
echo 'Tweets in english: <strong>'.$tweetsInEnglish.'</strong><br />';

?>