<?php

	public class HomePage extends Page{

		$weather = null;

		public function __construct(){
	        $this->name = "Home";
	        $this->pageId = "Home";
	        $this->svgIcon = "img/svg/home.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}

		function displayWeather(){
			return "";
		}
	}
	
?>