<?php
	class templateEngine{
		public $templates = null;

		public function __construct(){
	        $this->templates = array(
	        	"rss.container" => "templates/rss.container.html",
	        	"rss.storyItem" => "templates/rss.storyItem.html",
	        	"rss.storyContent" => "templates/rss.storycontent.html",
	        	"rss.storyContent.textonly" => "templates/rss.storycontent.textonly.html",
	        	"navigation.icon" => "templates/navigation.icon.html",
	        	"navigation.leftmenu.container" => "templates/navigation.leftmenu.container.html",
	        	"navigation.header" => "templates/navigation.header.html",
	        	"list.all" => "templates/list.all.html",
	        	"list.all.row" => "templates/list.all.row.html"
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