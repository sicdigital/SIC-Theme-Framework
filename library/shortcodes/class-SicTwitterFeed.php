<?php

// SAMPLE USE
// $twitterfeed = new SicTwitterFeed();
//  echo $twitterfeed->output();


class SicTwitterFeed extends SicShortcode{
	
 /**
    * getDefaultAtts
    * Public API - Override te set the default attributes
    *
    * @return NULL
    * @author Michael Chacon
    **/
    public function getDefaultAtts()
        {

        	$this->defaultAtts = array(
        		'class' => 'sic-twitter-feed', 
        		'count' => '5',
        		'username' => 'sicdigital',
        		'show_username' => false
        		);
            
            return $this->defaultAtts;
        }
      

	public function setContent($content){	

			$attributes = $this->getAttributes();

		    $feed = 'https://api.twitter.com/1/statuses/user_timeline.rss?screen_name='.$attributes[username].'&count='.$attributes[count];
		   
		    //gets the entire xml as a string and adds it to $feed string

			$xmlfeed = simplexml_load_file($feed);

			$tweets = $xmlfeed->channel;


	foreach ($tweets->item as $tweet) {

		//title
		//descrtiption
		//pubDate
		//guid
		//link
		$date = $tweet->pubDate;

		$content = $tweet->title;	
		
		//user selected show_username
		if(!$attributes['show_username']){
		
			//seoaretes the username: from the begining of the tweet
			$remove_username = explode($attributes['username'] . ': ', $content);
			
			//content is now plain text tweet without username
			$content = $remove_username['1'];	
		
		}

		//parses any links to a propper href tag and link
		$content = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank" class="tweet-link">$1$2$4</a>', $content);		
		
		//parses any @names to link to the profile
		$content = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\" class= \"twitter-handle\">@\\1</a>", $content);
		
		//parses any hashtags to be links to twitter search
		$content = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\" class=\"twitter-hash\">#\\1</a>", $content);
		
		$this->content[] = $content;
	
		}

        return $this;
		
	}//setContent
	
	public function output(){
	$attributes = $this->getAttributes();
    $tweetouts = $this->getContent();
   // echo $this->getAttributes();

    echo '<ul id="tweets_' . $attributes["id"] . '" class="' . $attributes["class"] . '" >';
        foreach ($tweetouts as $tweetout) {

            echo '<li>';
            echo $tweetout;     
            echo '</li>';
		}
	echo "</ul>";
	}
	
}

