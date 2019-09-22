<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>NYA Administration &gt; Wolfknives Member Registry Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php
	require_once("../inc/nav_main.php");
	DrawNavMain("Wolfknives Member Registry");
	?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="/admin/index.php">Home</a></li>
			<li><a href="/admin/wolfknives-registry/index.php">Wolfknives Member Registry</a></li>
			<li class="active"><?php if($wkMember->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Wolfknives Member</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>&nbsp;</h2>
				<div class="btn-group">
					<a href="index.php" class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span> <strong>Go back to WK Members</strong></a>
				</div><!-- /btn-group -->
			</div>
			<div class="col-sm-8">
				<h1>Wolfknives Member Registry <small>Database</small></h1>
				<h3><?php if($wkMember->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Wolfknives Name</h3>
				<?php if (isset($msg) && $msg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
				<form name="editWKMember" method="post" action="controller.php" class="form-horizontal" role="form">
				<input type="hidden" name="action" value="Save">
				<input type="hidden" name="id" value="<?php echo $wkMember->getId() ?>">
					<div class="form-group">
						<label for="publish" class="col-sm-2 control-label">Publish</label>
						<div class="col-sm-10">
							<input type="checkbox"<?php if($wkMember->getPublish() == 1){ ?> checked<?php } ?> value="1" id="publish" name="publish"> Check for yes, leave blank for no
						</div>
					</div>
					<div class="form-group">
						<label for="firstNameReal" class="col-sm-2 control-label">First Name</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getFirstNameReal() ?>" placeholder="Name..." class="form-control" id="firstNameReal" name="firstNameReal">
							<p class="help-block">* Required</p>
						</div>
					</div>
					<div class="form-group">
						<label for="lastNameReal" class="col-sm-2 control-label">Last Name</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getLastNameReal() ?>" placeholder="Name..." class="form-control" id="lastNameReal" name="lastNameReal">
						</div>
					</div>
					<div class="form-group">
						<label for="nameWK" class="col-sm-2 control-label">Wolfknives Name</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getNameWK() ?>" placeholder="Wolfknives Name..." class="form-control" id="nameWK" name="nameWK">
							<p class="help-block">* Required</p>
						</div>
					</div>
					<div class="form-group">
						<label for="nameTwitter" class="col-sm-2 control-label">Twitter Name</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getNameTwitter() ?>" placeholder="Twitter Name..." class="form-control" id="nameTwitter" name="nameTwitter">
							<p class="help-block">(No need to add the @ symbol)</p>
						</div>
					</div>
					<div class="form-group">
						<label for="nameInstagram" class="col-sm-2 control-label">Instagram Name</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getNameInstagram() ?>" placeholder="Instagram Name..." class="form-control" id="nameInstagram" name="nameInstagram">
							<p class="help-block">(No need to add the @ symbol)</p>
						</div>
					</div>
					<div class="form-group">
						<label for="nameOJE" class="col-sm-2 control-label">Official Jaosn Ellis.com Name</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getNameOJE() ?>" placeholder="OfficialJasonEllis.com Name..." class="form-control" id="nameOJE" name="nameOJE">
						</div>
					</div>
					<div class="form-group">
						<label for="state" class="col-sm-2 control-label">State / Province</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getState() ?>" placeholder="Name..." class="form-control" id="state" name="state">
						</div>
					</div>
					<div class="form-group">
						<label for="rank" class="col-sm-2 control-label">Rank</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getRank() ?>" placeholder="Rank..." class="form-control" id="rank" name="rank">
						</div>
					</div>
					<div class="form-group">
						<label for="accolades" class="col-sm-2 control-label">Accolades</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $wkMember->getAccolades() ?>" placeholder="Accolades..." class="form-control" id="accolades" name="accolades">
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
