<?php

	public class listsPage extends Page{

		public function __construct(){
	        $this->name = "Lists";
	        $this->pageId = "Lists";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}
	}
	
?>