<?php

	include 'Main.php';

	include 'NoaaWeather.php';

?>
<!doctype html>
<html lang="en-US">
	<head>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/myScript.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"></style>
		<link rel="stylesheet" type="text/css" href="css/myStyle.css"></style>
	</head>
	<body>
		<div class="leftMenu">
			<?php echo displayNavigationMenu($pages); ?>
		</div>
		<div class="container">
			<?php 
			echo displayName($pages, $_GET, $_POST);
			echo processPage($pages, $_GET, $_POST);
			?>
		</div>
	</body>
</html>