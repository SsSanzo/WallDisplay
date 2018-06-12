<?php
	class templateEngine{
		public $templates = null;

		public function __construct(){
	        $this->templates = array(
	        	"rss.container" => "templates/rss.container.html",
	        	"rss.storyItem" => "templates/rss.storyItem.html",
	        	"navigation.icon" => "templates/navigation.icon.html"
	        );
	    }

		public function render(string $templateId = null, array $values = null): string{
			if(!isset($templateId) OR !isset($values)) return "templateEngine:render() unidentified template";
			if(file_exists($this->templates["$templateId"])){
				$data = file_get_contents($this->templates["$templateId"]);
				foreach ($values as $key => $value) {
					$data = str_replace("{{" . $key . "}}", $value, $data);
				}
				return $data;
			}else{
				 return "templateEngine:render() template does not exist";
			}
		}
	}

?>