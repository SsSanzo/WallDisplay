<?php
	abstract class Page{
		public int $pageId;
		public string $name;
		public string $svgIcon;

		abstract function process(array $getParams, array $postParams): string;

		public function displayIcon(){
			return "<img src='" . $this->svgIcon . "' height='32' alt='" . $this->name . " data-page='" . $this->pageId . "'/>"
		}
	}
?>