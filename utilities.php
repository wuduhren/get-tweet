<?
$CONSUMER_KEY = 'YOUR_CONSUMER_KEY';
$CONSUMER_SECRET = 'YOUR_CONSUMER_SECRET';

// -----------------------------------------------------------------------------
function get_request($name, $optional=true, $default=null){
    if (!isset($_REQUEST[$name])) {
        if ($optional) { return $default; }
        die('ERROR: get '.$name.' failed.');
    }
    return $_REQUEST[$name];
}

// -----------------------------------------------------------------------------
function get_bearer_token(){
	global $CONSUMER_KEY, $CONSUMER_SECRET;

	$url = 'https://api.twitter.com/oauth2/token';
	$consumer_key = urlencode($CONSUMER_KEY);
	$consumer_secret = urlencode($CONSUMER_SECRET);
	$bearer_token = '';
	
	$headers = array( 
		'POST /oauth2/token HTTP/1.1', 
		'Host: api.twitter.com', 
		'Authorization: Basic '.base64_encode($consumer_key.':'.$consumer_secret),
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
	); 

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
	$header = curl_setopt($ch, CURLOPT_HEADER, 1);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$data = curl_exec($ch);
	curl_close($ch);

	$data = explode('\n', $data);
	foreach ($data as $line) {
		if ($line === false) {
			//no bearer token
		} else {
			$bearer_token = $line;
		}
	}
	$bearer_token = json_decode($bearer_token)->access_token;
	echo $bearer_token;
	return $bearer_token;
}

function invalidate_bearer_token($bearer_token){
	global $CONSUMER_KEY, $CONSUMER_SECRET;

	$url = 'https://api.twitter.com/oauth2/invalidate_token';
	$consumer_key = urlencode($CONSUMER_KEY);
	$consumer_secret = urlencode($CONSUMER_SECRET);

	$headers = array( 
		'POST /oauth2/invalidate_token HTTP/1.1', 
		'Host: api.twitter.com',
		'Authorization: Basic '.base64_encode($consumer_key.':'.$consumer_secret),
		'Accept: */*',
		'Content-Length: 119',
		'Content-Type: application/x-www-form-urlencoded'
	); 
    
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'access_token='.$bearer_token);
	$header = curl_setopt($ch, CURLOPT_HEADER, 1);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$data = curl_exec ($ch);
	curl_close($ch);

	echo $data;
	return $data;
}


?>