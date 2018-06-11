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
			$buffer = array();

			foreach ($this->rssFeeds as $feed) {
				$buffer[] = displayFeed($feed);
			}

			return implode("", $buffer);
		}

		public function displayFeed($feedURL = ""){
			If(strcmp("", $feedURL)) return "";

			$engine = new templateEngine();
			$buffer = array();

			$xmlDoc = new DOMDocument();
			if(!$xmlDoc->load($feedURL)) return "newsPage:displayFeed() unable to load RSS feed";

			$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
			$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
			$stories=$xmlDoc->getElementsByTagName('item');

			foreach ($stories as $story) {
				$title = $story->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
				$description = $story->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
				$buffer[] = $engine->render("rss.storyItem", array("title" => $title, "description" => $description));
			}

			$engine->render("rss.container", array("title" => $channel_title, "channel" => $channel, "items" => implode("", $buffer)));

		}
	}
	
?>