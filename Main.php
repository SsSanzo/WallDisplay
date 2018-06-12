<?php

	include "interface.page.php";
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
			$list[] = "<LI>" . $engine->render("navigation.icon", array("pageName" => $page->name, "pageId" => $page->pageId, "iconURL" => $page->svgIcon, )) . "</LI>";
		}
		return "<UL>" . implode("", $list) . "</UL>";
	}

	function processPage(array $pages, array $getParams, array $postParams): string{
		$tagetPage = $getParams["pt"] ?? $pages[1]->pageId;
		foreach ($pages as $page) {
			if(strcasecmp($page->pageId, $tagetPage)==0){
				return $page->process($getParams, $postParams);
			}
		}
	}

?>