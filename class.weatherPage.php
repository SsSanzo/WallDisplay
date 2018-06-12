<?php

	public class weatherPage extends Page{

		$weather = null;

		public function __construct(){
	        $this->name = "Weather";
	        $this->pageId = "Weather";
	        $this->svgIcon = "img/svg/weather.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}

		function displayWeather(){
			return "";
		}
	}
	
?>