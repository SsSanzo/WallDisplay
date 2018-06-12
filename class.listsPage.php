<?php

	class listsPage extends Page{

		public function __construct(){
	        $this->name = "Lists";
	        $this->pageId = "Lists";
	        $this->svgIcon = "img/svg/list.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}
	}
	
?>