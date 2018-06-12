<?php

	class medicinePage extends Page{

		public function __construct(){
	        $this->name = "Medicine";
	        $this->pageId = "Medicine";
	        $this->svgIcon = "img/svg/medicine.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}
	}
	
?>