<?php

	class HomePage extends Page{

		public $weather = null;

		public function __construct(){
	        $this->name = "Home";
	        $this->pageId = "Home";
	        $this->svgIcon = "img/svg/home.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}

		function displayWeather(){
			return "";
		}
	}
	
?>