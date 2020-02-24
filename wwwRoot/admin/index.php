<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>NYA Administration</title>
		<?php include_once("inc/meta-data.php"); ?>
	</head>
	<body>
		<?php include_once("inc/nav_main.php"); DrawNavMain("Home"); ?>
		<main role="main" class="container">
			<h1 class="display-3">Hello <?php echo $_SERVER['REMOTE_USER']; ?></h1>
			<p class="lead">These areas are for data that are not stored in a typical &quot;blog&quot; installation, making for easier core updates.</p>
			<p class="lead">Use the navigation above to select an area you would like to manage.</p>
			<p class="text-danger"><em>All additions, edits, and deletions are instantly reflected on the public website.</em></p>
		</main>
		<?php include_once("inc/footer.php"); ?>
	</body>
</html>