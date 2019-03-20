<?php
require('utilities.php');

$BEARER_TOKEN = 'YOUR_BEARER_TOKEN';

get_tweet();

// -----------------------------------------------------------------------------
function get_tweet(){
	$hashtag = get_request('hashtag');
	$from = get_request('from');
	$count = get_request('count');

	$target = '';
	if (isset($hashtag)) {
		foreach (explode(',', $hashtag) as $h) {
			if ($h=='') { continue; }
			$target .= '#'.$h.' OR ';
		}
	}
	if (isset($from)) {
		foreach (explode(',', $from) as $user) {
			if ($user=='') { continue; }
			$target .= 'from:'.$user.' OR ';
		}
	}
	$target = substr($target, 0, -4); //remove the last 'OR'

	$data = search($target, $count);
	die($data);
}

function search($target, $count=null, $other_query=null){
	global $BEARER_TOKEN;
	if (!isset($target) || $target=='') { return ''; }

	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	$query ='?q='.urlencode(trim($target));

	if (isset($count) && intval($count)) { $query = $query.'&count='.$count; }
	if (isset($other_query)) { $query = $query.'&'.$other_query; }

	$headers = array( 
		'GET /1.1/search/tweets.json'.$query.' HTTP/1.1', 
		'Host: api.twitter.com',
		'Authorization: Bearer '.$BEARER_TOKEN
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url.$query);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}


?>