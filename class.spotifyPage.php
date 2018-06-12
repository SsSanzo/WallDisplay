<?php

	class spotifyPage extends Page{

		public function __construct(){
	        $this->name = "Spotify";
	        $this->pageId = "Music";
	        $this->svgIcon = "img/svg/player.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}
	}
	
?>