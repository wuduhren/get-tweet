#Get Tweet

##Usage
1. Parameters. There are 3 parameters, `hashtag`, `from`, `count`.

	`hashtag`: Search the tweets with certain hashtag. You don't need to include the `#`. If you are looking for multiple hashtags, seperate the value by comma (`,`).
	
	`from`: Search the tweets sent from certain user account. If you are looking for tweets from multiple user accounts, seperate the value by comma (`,`).
	
	`count`: The max number of tweets return. The default is 15, maximum 100.
	
2. Example

	Get the tweets that contain `#75hard` for maximum 4.

	```
	http://localhost/get-tweet/tweet.php?hashtag=75hard&count=4
	```
	
	Get the tweets that contain `#75hard` or `#taiwan` (or both).

	```
	http://localhost/get-tweet/tweet.php?hashtag=75hard,taiwan
	```	
	
	Get the tweets from `KingJames`.
	
	```
	http://localhost/get-tweet/tweet.php?from=KingJames
	```
	
	Get the tweets from `KingJames` or contain `#75hard ` or contain `#taiwan `.
	
	```
	http://localhost/get-tweet/tweet.php?hashtag=75hard,taiwan&from=KingJames
	```	

3. Return data

	```
	{
		statuses: [
			{...},
			{...},
			{...},
			{...}
		],
		search_metadata: {
			completed_in: 0.033,
			max_id: 1107824539931758600,
			max_id_str: "1107824539931758592",
			next_results: "?max_id=1106401684497018879&q=from%3AKingJames&count=4&include_entities=1",
			query: "from%3AKingJames",
			refresh_url: "?since_id=1107824539931758592&q=from%3AKingJames&include_entities=1",
			count: 4,
			since_id: 0,
			since_id_str: "0"
		}
	}
	```
	
	There are 4 tweets in `statuses `. There are some info about the search we made in `search_metadata `. It is useful for debug. There are many information in each tweet, so I will not show them all. Some data are worth noticing, such as.
	
	```
	created_at: create time.
	text: the tweet context.
	user->name: user name.
	entities->hashtags: the hashtags in this tweet.
	urls->url: the url of this tweet (useful for debuging).
	```

##Get Bearer Token
1. To use bearer tokens, we need to create a developer account.
<https://developer.twitter.com/en/docs/basics/authentication/guides/bearer-tokens>

2. Create an Twiter APP. For the website feild, just key in any website, it doesn't matter.

3. Get bearer token. Replace the `YOUR_API_KEY` and `YOUR_API_SECRET` below and copy it to terminal.

	```
	curl -u 'YOUR_API_KEY:YOUR_API_SECRET' --data 'grant_type=client_credentials' 'https://api.twitter.com/oauth2/token'
	```
	The return looks like
	
	```
	{"token_type":"bearer","access_token":"YOUR_BEARER_TOKEN"}
	```

4. Set the `BEARER_TOKEN` in `tweet.php`.
	
##Deploy
1. I am running on `MAMP` local server for testing.

	```
	PHP Version 5.6.32
	macOS 10.13.6
	```

	You can install `MAMP` from <https://www.mamp.info/en/downloads/> on MAC.

2. Put the `get-tweet` directory under your `www/`. For simplexity, I use the browser for testing and demonstrating.

3. Set the `BEARER_TOKEN` in `tweet.php`. I use the `BEARER_TOKEN` because we don't need the information that needs login to get; it is more suitable for our purpose.

##What's Next
1. I will store the `BEARER_TOKEN` in a seperated file. Get or invalidate it by private function. The purpose is to keep it from public access and manipulate it by hand.

2. There are many complicated search we can do by Twitter API.

##Resources
<https://developer.twitter.com/en/docs/tweets/search/api-reference/get-search-tweets.html>

<https://developer.twitter.com/en/docs/tweets/search/guides/standard-operators>

<https://github.com/jonhurlock/Twitter-Application-Only-Authentication-OAuth-PHP>