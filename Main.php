<?php

	const ERROR_LOG_FILE = "myError.log";

	include "interface.page.php";
	include "class.db.php";
	include "class.templateEngine.php";
	include "class.homePage.php";
	include "class.weatherPage.php";
	include "class.listsPage.php";
	include "class.medicinePage.php";
	include "class.newsPage.php";
	include "class.spotifyPage.php";
	include "class.calendarPage.php";
	include "class.settingsPage.php";

	$pages = array(
		new HomePage(),
		new weatherPage(),
		new listsPage(),
		new newsPage(),
		new spotifyPage(),
		new medicinePage(),
		new calendarPage(),
		new settingsPage()
	);

	function displayNavigationMenu(array $pages): string{

		$engine = new templateEngine();
		$list = array();
		foreach ($pages as $page) {
			$list[] = $engine->render("navigation.icon", array("pageName" => $page->name, "pageId" => $page->pageId, "iconURL" => $page->svgIcon, ));
		}
		return $engine->render("navigation.leftmenu.container",array("iconList" => implode("", $list)));
	}

	function processPage(array $pages, array $getParams, array $postParams): string{
		$tagetPage = $getParams["pt"] ?? $pages[0]->pageId;
		foreach ($pages as $page) {
			if(strcasecmp($page->pageId, $tagetPage)==0){
				return $page->process($getParams, $postParams);
			}
		}
		return "";
	}

	function displayName(array $pages, array $getParams, array $postParams): string{
		
		$engine = new templateEngine();
		$tagetPage = $getParams["pt"] ?? $pages[0]->pageId;
		foreach ($pages as $page) {
			if(strcasecmp($page->pageId, $tagetPage)==0){
				return $engine->render("navigation.header", array("name" => $page->name));
			}
		}
		return $engine->render("navigation.header", array("name" => "Page does not exist"));
	}

?>