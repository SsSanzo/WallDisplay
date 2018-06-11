<?php

	public class calendarPage extends Page{

		public function __construct(){
	        $this->name = "Home";
	        $this->pageId = "Home";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function displayContent(){
			return "";
		}
	}
	
?>