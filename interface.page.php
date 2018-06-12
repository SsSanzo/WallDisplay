<?php
	abstract class Page{
		public $pageId;
		public $name;
		public $svgIcon;

		abstract function process(array $getParams, array $postParams): string;

		public function displayIcon(){
			return "<img src='" . $this->svgIcon . "' height='32' alt='" . $this->name . " data-page='" . $this->pageId . "'/>";
		}
	}
?>