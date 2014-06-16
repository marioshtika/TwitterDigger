<h1>Collect Tweets</h1>
<hr />

<?php
require_once('lib/Phirehose.php');
require_once('lib/OauthPhirehose.php');

/**
 * Example of using Phirehose to display a live filtered stream using track words 
 */
class FilterTrackConsumer extends OauthPhirehose {
	/**
	 * Enqueue each status
	 * 
	 * @param string $status
	 */
		
	public function enqueueStatus($status) {
		/*
		 * In this simple example, we will just display to STDOUT rather than enqueue.
		 * NOTE: You should NOT be processing tweets at this point in a real application, instead they should be being
		 * enqueued and processed asyncronously from the collection process. 
		 */
		
		$data = json_decode($status, true);
		
		if (is_array($data) && isset($data['user']['screen_name'])) {
			// connect to mongo db
			$m = new MongoClient();

			// select a database
			$db = $m->twitter_db;

			// select a collection (analogous to a relational database's table)
			$collection = $db->TwitterDigger;
			
			$collection->insert($data);

			echo urldecode($data['text']) . "<br />";
		}
	}
}

// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", "RNErkai2jufnwn8tYt4pzmZbR");
define("TWITTER_CONSUMER_SECRET", "xaorDVbLRTqIplLuUxbAB9TbDAqLpJiqizhFHRBrcALgYySW45");

// The OAuth data for the twitter account
define("OAUTH_TOKEN", "92730228-O0URDlvT4ytLB76Os8JdZaXuDM7cxRwNYVPrFvTFq");
define("OAUTH_SECRET", "wsfG5R1n3WMyQk1Cn3fRPjVLhclJ7c4BoV1sLabTNXBPq");

if(isset($_POST['keywords'])){
	
	echo "Prepare Twitter Digger table: <strong>ok</strong><br />";

	echo '<hr />';

	echo '<a class="btn btn-danger" href="#" onclick="window.stop();" role="button">Stop Collecting</a><br /><br />';
	
	// connect to mongo db
	$m = new MongoClient();

	// select a database
	$db = $m->twitter_db;

	// select a collection (analogous to a relational database's table)
	$collection = $db->TwitterDigger;

	// clean table (remove all documents)
	$collection->remove();

	$keywordsArray = explode(",", $_POST['keywords']);
	
	// Start streaming
	$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
	$sc->setTrack($keywordsArray);
	// $sc->setTrack(array('ukraine', 'UkraineElections', 'UnitedForUkraine',  'UkraineVotes', 'Ukrainian'));
	$sc->consume();
} else {
	?>
	<form class="form-inline" role="form" method="post" action="index.php?route=collect-tweets">
		<div class="form-group">
			<input type="text" name="keywords" class="form-control" id="inputKeywords" placeholder="Enter keywords...">
		</div>
		<button type="submit" class="btn btn-default">Search</button>
	</form>
	<small>Split keywords with comma ","</small>
	<?php
}
?>