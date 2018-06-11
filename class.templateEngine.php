<?php

	public class templateEngine{
		public $templates;

		public function __construct(){
	        $this->templates = array(
	        	"templateId" => "filepath"
	        );
	    }

		public render(string $templateId = null, array $values = null): string{

		}
	}

?>