<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>NYA Administration &gt; TJES Quote Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php
	require_once("../inc/nav_main.php");
	DrawNavMain("Quotes");
	?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="/admin/index.php">Home</a></li>
			<li><a href="/admin/quotes/index.php">TJES Quotes</a></li>
			<li class="active"><?php if($quote->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Quote</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>&nbsp;</h2>
				<div class="btn-group">
					<a href="index.php" class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span> <strong>Go back to Quotes</strong></a>
				</div><!-- /btn-group -->
			</div>
			<div class="col-sm-8">
				<h1>TJES Guest <small>Database</small></h1>
				<h3><?php if($quote->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Quote</h3>
				<?php if (isset($msg) && $msg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
				<form name="editQuote" method="post" action="controller.php" class="form-horizontal" role="form">
				<input type="hidden" name="action" value="Save">
				<input type="hidden" name="id" value="<?php echo $quote->getId() ?>">
					<div class="form-group">
						<label for="publish" class="col-sm-2 control-label">Publish</label>
						<div class="col-sm-10">
							<input type="checkbox"<?php if($quote->getPublish() == 1){ ?> checked<?php } ?> value="1" id="publish" name="publish"> Check for yes, leave blank for no
						</div>
					</div>
					<div class="form-group">
						<label for="author" class="col-sm-2 control-label">Author</label>
						<div class="col-sm-10">
							<select size="1" class="form-control" id="author" name="author">
								<option value="Jason Ellis"<?php if($quote->getAuthor()=="Jason Ellis"){ ?> selected<?php } ?>>Jason Ellis</option>
								<option value="Micheal Tully"<?php if($quote->getAuthor()=="Micheal Tully"){ ?> selected<?php } ?>>Micheal Tully</option>
								<option value="Josh Richmond"<?php if($quote->getAuthor()=="Josh Richmond"){ ?> selected<?php } ?>>Josh &quot;Rawdog&quot; Richmond</option>
								<option value="Will Pendarvis"<?php if($quote->getAuthor()=="Will Pendarvis"){ ?> selected<?php } ?>>Will &quot;Shiny Shins&quot; Pendarvis</option>
								<option value="Kevin Kraft"<?php if($quote->getAuthor()=="Kevin Kraft"){ ?> selected<?php } ?>>Kevin &quot;Cumtard&quot; Kraft</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="body" class="col-sm-2 control-label">Quote</label>
						<div class="col-sm-10">
							<textarea rows="5" class="form-control" id="body" name="body"><?php echo $quote->getBody() ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="context" class="col-sm-2 control-label">Context</label>
						<div class="col-sm-10">
							<textarea rows="5" class="form-control" id="context" name="context"><?php echo $quote->getContext() ?></textarea>
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
