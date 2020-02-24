<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>NYA Administration &gt; TJES Quote Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php require_once("../inc/nav_main.php"); DrawNavMain("Quotes"); ?>
	<main role="main" class="container">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/admin/index.php">Home</a></li>
			<li class="breadcrumb-item"><a href="/admin/quotes/index.php">TJES Quotes</a></li>
			<li class="breadcrumb-item active"><?php if($quote->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Quote</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
				<h2>&nbsp;</h2>
				<p><a href="index.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-reply"></i> Go Back</a></p>
			</div>
			<div class="col-sm-8 main">
				<h1>TJES Guest <small>Database</small></h1>
				<h3><?php if($quote->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Quote</h3>
				<hr>
				<?php if (isset($msg) && $msg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
				<form name="editQuote" method="post" action="controller.php" role="form">
					<input type="hidden" name="action" value="Save">
					<input type="hidden" name="id" value="<?php echo $quote->getId() ?>">
					<div class="form-group form-check">
						<input class="form-check-input" type="checkbox"<?php if($quote->getPublish() == 1){ ?> checked<?php } ?> value="1" id="publish" name="publish">
						<label class="form-check-label" for="publish">Publish <small>(Check for yes, leave blank for no)</small></label>
					</div>
					<div class="form-group">
						<label for="author">Author</label>
						<select size="1" class="form-control" id="author" name="author">
							<option value="Jason Ellis"<?php if($quote->getAuthor()=="Jason Ellis"){ ?> selected<?php } ?>>Jason Ellis</option>
							<option value="Micheal Tully"<?php if($quote->getAuthor()=="Micheal Tully"){ ?> selected<?php } ?>>Micheal Tully</option>
							<option value="Josh Richmond"<?php if($quote->getAuthor()=="Josh Richmond"){ ?> selected<?php } ?>>Josh &quot;Rawdog&quot; Richmond</option>
							<option value="Will Pendarvis"<?php if($quote->getAuthor()=="Will Pendarvis"){ ?> selected<?php } ?>>Will &quot;Shiny Shins&quot; Pendarvis</option>
							<option value="Kevin Kraft"<?php if($quote->getAuthor()=="Kevin Kraft"){ ?> selected<?php } ?>>Kevin &quot;Cumtard&quot; Kraft</option>
						</select>
					</div>
					<div class="form-group">
						<label for="body">Quote</label>
						<textarea rows="5" class="form-control" id="body" name="body"><?php echo $quote->getBody() ?></textarea>
					</div>
					<div class="form-group">
						<label for="context">Context</label>
						<textarea rows="5" class="form-control" id="context" name="context"><?php echo $quote->getContext() ?></textarea>
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
