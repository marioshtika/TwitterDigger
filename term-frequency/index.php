<h1>Term Frequency</h1>
<hr />
<?php

require 'preprocessor.php';

// for tweet preprocessing
$newTweet = new preprocessor();
  
// connect to mongo db
$m = new MongoClient();

// select a database
$db = $m->twitter_db;

// select a collection (analogous to a relational database's table)
$collection = $db->TwitterDigger;

echo "Prepare Term Frequency table: <strong>ok</strong><br />";

echo '<hr />';

// select a collection (analogous to a relational database's table)
$termCollection = $db->TermCollection;

// clean table (remove all documents)
$termCollection->remove();
		
// count rows in the collection
$allTweets = $collection->count();
echo 'All tweets: <strong>'.$allTweets.'</strong><br />';

// count english rows in the collection
$tweetsInEnglish = $collection->count(array('lang'=>'en'));
echo 'Tweets in english: <strong>'.$tweetsInEnglish.'</strong><br />';

// find everything in english from the collection
$documents = $collection->find(array('lang'=>'en'));

// iterate through the results
foreach ($documents as $tweet) {
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
			$termFrequency = $termCollection->findOne(array('term' => $term));
			if($termFrequency['frequency'] == NULL)	{
				$termCollection->insert(array(
					'term' => $term,
					'frequency' => 1)
				);
			} else {
				$nr_frequency = $termFrequency['frequency'] + 1;
				$termCollection->update(
					array('term' => $term),
					array('$set' => array('frequency' => $nr_frequency))
				);
			}
		}
	}
	
	/*
	// if you want to use the first 100 rows
	if($allTweets == 100) {
		break;
	}
	*/
}

echo '<hr />';

// show terms frequency
$limitRow = 10;
echo 'Show <strong>'.$limitRow.'</strong> most frequently terms used.<br /><br />';

$terms = $termCollection->find()->limit(10);
$terms = $terms->sort(array('frequency' => -1));
echo '<div class="Table">';
foreach ($terms as $term) {
	?>
	<div class="Row">
		<div class="Cell">
			<p>term: <strong><?php echo $term['term'];?></strong></p>
		</div>
		<div class="Cell">
			<p>frequency: <strong><?php echo $term['frequency'];?></strong></p>
		</div>
	</div>
	<?php
}
echo '</div>';
?>
