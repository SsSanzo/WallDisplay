<?php

	class newsPage extends Page{

		private $rssFeeds = array(
			"Le Monde" => "https://www.lemonde.fr/rss/une.xml",
			"NPR" => "https://www.npr.org/rss/rss.php?id=1001",
			"BBC" => "http://feeds.bbci.co.uk/news/world/rss.xml",
			"Economist" => "https://www.economist.com/sections/international/rss.xml"
		);

		public function __construct(){
	        $this->name = "News";
	        $this->pageId = "News";
	        $this->svgIcon = "img/svg/news.svg";
	    }

		public function displayFeed($feedURL = "", $feedname = null): string{
			If(strcmp("", $feedURL)==0){
				$errmsg = "newsPage:displayFeed() no feed to display";
				error_log($errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return $errmsg;
			}

			$engine = new templateEngine();
			$buffer = array();

			$xmlDoc = new DOMDocument();
			if(!$xmlDoc->load($feedURL)){
				$errmsg = "newsPage:displayFeed() unable to load RSS feed";
				error_log($errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return $errmsg;
			}
			$xmlDoc->normalize();

			$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
			$channel_title = $feedname ?? $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
			$stories=$xmlDoc->getElementsByTagName('item');

			foreach ($stories as $story) {

				$title = $story->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;

				$contentURL = $story->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;

				$description = "";
				$contentNode = $story->getElementsByTagName('encoded')->item(0);
				if(isset($contentNode)){
					$description = $engine->render("rss.storyContent.textonly", array("description" => $contentNode->childNodes->item(0)->nodeValue, "contentURL" => $contentURL));
				}else{
					$desc = $story->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
					$imgNode = $story->getElementsByTagName('enclosure')->item(0);
					if(isset($imgNode)){
						$enc = $imgNode->getAttribute("url");
						$description = $engine->render("rss.storyContent", array("description" => $desc, "pictureURL" => $enc, "contentURL" => $contentURL));	
					}else{
						$description = $engine->render("rss.storyContent.textonly", array("description" => $desc, "contentURL" => $contentURL));
					}
				}
				
				$buffer[] = $engine->render("rss.storyItem", array("title" => $title, "content" => $description));
			}

			return $engine->render("rss.container", array("title" => $channel_title, "items" => implode("", $buffer)));
		}

		public function process(array $getParams, array $postParams): string{
			$buffer = array();

			foreach ($this->rssFeeds as $feedname => $feed) {
				array_push($buffer, $this->displayFeed($feed, $feedname));
			}

			return implode("", $buffer);
		}
	}
	
?>