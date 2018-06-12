<?php
	abstract class Page{
		public $pageId;
		public $name;
		public $svgIcon;

		abstract function process(array $getParams, array $postParams): string;
	}
?>