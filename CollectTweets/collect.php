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
		
		$con = mysqli_connect("host", "username", "password", "database");
		
		// Check connection
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		mysqli_query($con, "INSERT INTO tweets3 (id, tweet) VALUES (NULL, '".$status."')");
		
		mysqli_close($con);
		
		/*		
		$data = json_decode($status, true);
		if (is_array($data) && isset($data['user']['screen_name'])) {
		print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "<br /><br />";
		}
		*/
	}
}

// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", "RNErkai2jufnwn8tYt4pzmZbR");
define("TWITTER_CONSUMER_SECRET", "xaorDVbLRTqIplLuUxbAB9TbDAqLpJiqizhFHRBrcALgYySW45");


// The OAuth data for the twitter account
define("OAUTH_TOKEN", "92730228-O0URDlvT4ytLB76Os8JdZaXuDM7cxRwNYVPrFvTFq");
define("OAUTH_SECRET", "wsfG5R1n3WMyQk1Cn3fRPjVLhclJ7c4BoV1sLabTNXBPq");

// Start streaming
$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->setTrack(array('ukraine', 'UkraineElections', 'UnitedForUkraine',  'UkraineVotes', 'Ukrainian'));
$sc->consume();