<?php

	include "interface.page.php";
	include "class.templateEngine.php";
	include "class.homePage.php";
	include "class.weatherPage.php";
	include "class.listsPage.php";
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
		new calendarPage(),
		new settingsPage()
	);

	function displayNavigationMenu(array $pages): string{
		
		$list = array();
		foreach ($pages as $page) {
			array_push($list, "<LI>" . $page.displayIcon() . "</LI>");
		}
		return "<UL>" . implode("", $list) . "</UL>"
	}

	function processPage(array $pages, array $getParams, array $postParams): string{
		$tagetPage = $getParams["pt"] ?? $pages[1]->pageId;
		foreach ($pages as $page) {
			if(strcasecmp($page->pageId, $tagetPage)){
				$page->process($getParams, $postParams);
			}
		}
	}

?>