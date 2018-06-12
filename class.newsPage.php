<?php

	class newsPage extends Page{

		private $rssFeeds = array(
			"https://www.npr.org/rss/rss.php?id=1001",
			"http://feeds.bbci.co.uk/news/world/rss.xml"
		);

		public function __construct(){
	        $this->name = "News";
	        $this->pageId = "News";
	        $this->svgIcon = "img/svg/news.svg";
	    }

		public function displayFeed($feedURL = ""): string{
			If(strcmp("", $feedURL)==0) return "newsPage:displayFeed() no feed to display";

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

			return $engine->render("rss.container", array("title" => $channel_title, "items" => implode("", $buffer)));
		}

		public function process(array $getParams, array $postParams): string{
			$buffer = array();

			foreach ($this->rssFeeds as $feed) {
				array_push($buffer, $this->displayFeed($feed));
			}

			return implode("", $buffer);
		}
	}
	
?>