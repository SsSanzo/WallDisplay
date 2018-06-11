<?php

	include "interface.page.php";
	include "class.homePage.php";
	include "class.weatherPage.php";
	include "class.listsPage.php";
	include "class.newsPage.php";
	include "class.spotifyPage.php";
	include "class.calendarPage.php";
	include "class.settingsPage.php";


	function displayNavigationMenu(): string{
		$pages = array(
			new HomePage(),
			new weatherPage(),
			new listsPage(),
			new newsPage(),
			new spotifyPage(),
			new calendarPage(),
			new settingsPage()
		);

		$list = array();
		foreach ($pages as $page) {
			array_push($list, "<LI>" . $page.displayIcon() . "</LI>");
		}
		return "<UL>" . implode("", $list) . "</UL>"
	}

?>