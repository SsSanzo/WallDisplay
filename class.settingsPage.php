<?php

	class settingsPage extends Page{

		public function __construct(){
	        $this->name = "Settings";
	        $this->pageId = "Settings";
	        $this->svgIcon = "img/svg/settings.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}
	}
	
?>