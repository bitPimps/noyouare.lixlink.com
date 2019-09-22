<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>NYA Administration &gt; Ellis English Dictionary Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php
	require_once("../inc/nav_main.php");
	DrawNavMain("EED");
	?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="/admin/index.php">Home</a></li>
			<li><a href="/admin/eed/index.php">Ellis English Dictionary</a></li>
			<li class="active"><?php if($ellisWord->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Term</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>&nbsp;</h2>
				<div class="btn-group">
					<a href="index.php" class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span> <strong>Go back to EED</strong></a>
				</div><!-- /btn-group -->
			</div>
			<div class="col-sm-8">
				<h1>EED <small>Database</small></h1>
				<h3><?php if($ellisWord->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Term</h3>
				<?php if (isset($msg) && $msg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
				<form name="editGuest" method="post" action="controller.php" class="form-horizontal" role="form">
				<input type="hidden" name="action" value="Save">
				<input type="hidden" name="id" value="<?php echo $ellisWord->getId() ?>">
					<div class="form-group">
						<label for="publish" class="col-sm-2 control-label">Publish</label>
						<div class="col-sm-10">
							<input type="checkbox"<?php if($ellisWord->getPublish() == 1){ ?> checked<?php } ?> value="1" id="publish" name="publish"> Check for yes, leave blank for no
						</div>
					</div>
					<div class="form-group">
						<label for="term" class="col-sm-2 control-label">Term</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $ellisWord->getTerm() ?>" placeholder="Term..." class="form-control" id="term" name="term">
							<p class="help-block">* Required</p>
						</div>
					</div>
					<div class="form-group">
						<label for="definition" class="col-sm-2 control-label">Definition</label>
						<div class="col-sm-10">
							<textarea rows="5" class="form-control" id="definition" name="definition"><?php echo $ellisWord->getDefinition() ?></textarea>
							<p class="help-block">* Required</p>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok-circle"></span> <strong>Save</strong></button> <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> <strong>Clear</strong></button>
						</div>
					</div>
				</form>
			</div><!-- /.col-sm-8 -->
		</div><!-- /.row -->
		<?php require_once("../inc/footer.php"); ?>
	</div><!-- /container -->
	<?php require_once("../inc/footer_scripts.php"); ?>
	</body>
</html>
