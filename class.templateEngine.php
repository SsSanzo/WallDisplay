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
	        	"list.all.row" => "templates/list.all.row.html",
	        	"list.one" => "templates/list.one.html",
	        	"list.one.item" => "templates/list.one.item.html",
	        	"list.settings" => "templates/list.settings.html"
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
				$errmsg = "templateEngine:render(" . $templateId . ") template does not exist";
				error_log($errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return $errmsg;
			}
		}
	}

?>