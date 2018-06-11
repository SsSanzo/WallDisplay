<?php

	public class newsPage extends Page{

		$rssFeeds = array(
			"https://www.npr.org/rss/rss.php?id=1001"
		);

		public function __construct(){
	        $this->name = "News";
	        $this->pageId = "News";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}

		public function displayFeed($feedURL = ""){
			If(strcmp("", $feedURL)) return "";

			$xmlDoc = new DOMDocument();
			$xmlDoc->load($feedURL);

			$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
			$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
			$stories=$xmlDoc->getElementsByTagName('item');

			foreach ($stories as $story) {
				$title = $story->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
				$description = $story->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
			}

		}
	}
	
?>