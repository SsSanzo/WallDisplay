<?php

	public class newsPage extends Page{

		public function __construct(){
	        $this->name = "News";
	        $this->pageId = "News";
	        $this->svgIcon = "/img/svg/Home.svg";
	    }

		public function process(array $getParams, array $postParams){
			return "";
		}
	}
	
?>