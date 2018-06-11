<?php

	public class settingsPage extends Page{

		public function __construct(){
	        $this->name = "Settings";
	        $this->pageId = "Settings";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}
	}
	
?>