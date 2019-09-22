<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>NYA Administration</title>
		<?php include_once("inc/meta-data.php"); ?>
	</head>
	<body>
	<?php
	require_once("inc/nav_main.php");
	DrawNavMain("Home");
	?>
	<div class="container">
		<ol class="breadcrumb">
			<li class="active">Home</li>
		</ol>
		<div class="text-center">
			<h1>Hello <?php echo $_SERVER['REMOTE_USER']; ?></h1>
			<p class="lead">These areas are for data that are not stored in a typical &quot;blog&quot; installation, making for easier core updates.</p>
			<p class="lead">Use the navigation above to select an area you would like to manage.</p>
		</div>
		<?php require_once("inc/footer.php"); ?>
	</div><!-- /container -->
	<?php require_once("inc/footer_scripts.php"); ?>
	</body>
</html>
