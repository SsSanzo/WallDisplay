<?php
	include 'main.php';

	include 'NoaaWeather.php';
?>
<!doctype html>
<html lang="en-US">
	<head>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/myScript.js"></script>
		<link rel="stylesheet" type="text/css" href="css/myStyle.css"></style>
	</head>
	<body>
		<div class="leftMenu">
			<ul>
				<?php echo displayNavigationMenu($pages); ?>
			</ul>
		</div>
		<div class="container">
			<?php echo processPage($pages, $_GET, $_POST); ?>
		</div>
	</body>
</html>