<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>NYA Administration &gt; Ellis English Dictionary Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php require_once("../inc/nav_main.php"); DrawNavMain("EED"); ?>
	<main role="main" class="container">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/admin/index.php">Home</a></li>
			<li class="breadcrumb-item"><a href="/admin/eed/index.php">Ellis English Dictionary</a></li>
			<li class="breadcrumb-item active"><?php if($ellisWord->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Term</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
				<h2>&nbsp;</h2>
				<p><a href="index.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-reply"></i> Go Back</a></p>
			</div>
			<div class="col-sm-8 main">
				<h1>EED <small>Database</small></h1>
				<h3><?php if($ellisWord->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Term</h3>
				<hr>
				<?php if (isset($msg) && $msg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
				<form name="editGuest" method="post" action="controller.php" role="form">
					<input type="hidden" name="action" value="Save">
					<input type="hidden" name="id" value="<?php echo $ellisWord->getId() ?>">
					<div class="form-group form-check">
						<input class="form-check-input" type="checkbox"<?php if($ellisWord->getPublish() == 1){ ?> checked<?php } ?> value="1" id="publish" name="publish">
						<label class="form-check-label" for="publish">Publish <small>(Check for yes, leave blank for no)</small></label>
					</div>
					<div class="form-group">
						<label for="term">Term</label>
						<input type="text" value="<?php echo $ellisWord->getTerm() ?>" placeholder="Term..." class="form-control" id="term" name="term">
						<small class="form-text text-muted">* Required</small>
					</div>
					<div class="form-group">
						<label for="definition">Definition</label>
						<textarea rows="5" class="form-control" id="definition" name="definition"><?php echo $ellisWord->getDefinition() ?></textarea>
						<small class="form-text text-muted">* Required</small>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button> <button type="reset" class="btn btn-danger"><i class="fa fa-undo" aria-hidden="true"></i> Clear</button>
					</div>
				</form>
			</div><!-- /.col-sm-8 -->
		</div><!-- /.row -->
	</main><!-- /container -->
	<?php require_once("../inc/footer.php"); ?>
	</body>
</html>
