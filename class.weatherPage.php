<?php

	class weatherPage extends Page{

		public $weather = null;

		public function __construct(){
	        $this->name = "Weather";
	        $this->pageId = "Weather";
	        $this->svgIcon = "img/svg/weather.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}

		function displayWeather(){
			return "";
		}
	}
	
?>