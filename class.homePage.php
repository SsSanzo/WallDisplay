<?php

	public class HomePage extends Page{

		$weather = null;

		public function __construct(){
	        $this->name = "Home";
	        $this->pageId = "Home";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function displayContent(){
			return "";
		}

		function displayWeather(){
			return "";
		}
	}
	
?>