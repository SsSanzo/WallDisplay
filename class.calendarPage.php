<?php

	public class calendarPage extends Page{

		public function __construct(){
	        $this->name = "Calendar";
	        $this->pageId = "Calendar";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}
	}
	
?>