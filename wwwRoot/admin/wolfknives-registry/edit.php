<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>NYA Administration &gt; Wolfknives Member Registry Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php require_once("../inc/nav_main.php"); DrawNavMain("Wolfknives Member Registry"); ?>
	<main role="main" class="container">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/admin/index.php">Home</a></li>
			<li class="breadcrumb-item"><a href="/admin/wolfknives-registry/index.php">Wolfknives Member Registry</a></li>
			<li class="breadcrumb-item active"><?php if($wkMember->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Wolfknives Member</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
				<h2>&nbsp;</h2>
				<p><a href="index.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-reply"></i> Go Back</a></p>
			</div>
			<div class="col-sm-8 main">
				<h1>Wolfknives Member Registry <small>Database</small></h1>
				<h3><?php if($wkMember->getId()=="-1"){ ?>Add<?php } else { ?>Edit<?php } ?> a Wolfknives Name</h3>
				<hr>
				<?php if (isset($msg) && $msg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $msg; ?></div><?php } ?>
				<form name="editWKMember" method="post" action="controller.php" role="form">
					<input type="hidden" name="action" value="Save">
					<input type="hidden" name="id" value="<?php echo $wkMember->getId() ?>">
					<div class="form-group form-check">
						<input class="form-check-input" type="checkbox"<?php if($wkMember->getPublish() == 1){ ?> checked<?php } ?> value="1" id="publish" name="publish">
						<label class="form-check-label" for="publish">Publish <small>(Check for yes, leave blank for no)</small></label>
					</div>
					<div class="form-row">
						<div class="form-group col-lg-6">
							<label for="firstNameReal">First Name</label>
							<input type="text" value="<?php echo $wkMember->getFirstNameReal() ?>" placeholder="Name..." class="form-control" id="firstNameReal" name="firstNameReal">
							<small class="form-text text-muted">* Required</small>
						</div>
						<div class="form-group col-lg-6">
							<label for="lastNameReal">Last Name</label>
							<input type="text" value="<?php echo $wkMember->getLastNameReal() ?>" placeholder="Name..." class="form-control" id="lastNameReal" name="lastNameReal">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-lg-6">
							<label for="nameWK">Wolfknives Name</label>
							<input type="text" value="<?php echo $wkMember->getNameWK() ?>" placeholder="Wolfknives Name..." class="form-control" id="nameWK" name="nameWK">
							<small class="form-text text-muted">* Required</small>
						</div>
						<div class="form-group col-lg-6">
							<label for="rank">Rank</label>
							<input type="text" value="<?php echo $wkMember->getRank() ?>" placeholder="Rank..." class="form-control" id="rank" name="rank">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-lg-4">
							<label for="nameTwitter">Twitter Name</label>
							<input type="text" value="<?php echo $wkMember->getNameTwitter() ?>" placeholder="Twitter Name..." class="form-control" id="nameTwitter" name="nameTwitter">
							<small class="form-text text-muted">(No need to add the @ symbol)</small>
						</div>
						<div class="form-group col-lg-4">
							<label for="nameInstagram">Instagram Name</label>
							<input type="text" value="<?php echo $wkMember->getNameInstagram() ?>" placeholder="Instagram Name..." class="form-control" id="nameInstagram" name="nameInstagram">
							<small class="form-text text-muted">(No need to add the @ symbol)</small>
						</div>
						<div class="form-group col-lg-4">
							<label for="nameOJE">Official Jaosn Ellis.com Name</label>
							<input type="text" value="<?php echo $wkMember->getNameOJE() ?>" placeholder="OfficialJasonEllis.com Name..." class="form-control" id="nameOJE" name="nameOJE">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-lg-6">
							<label for="state">State / Province</label>
							<input type="text" value="<?php echo $wkMember->getState() ?>" placeholder="Name..." class="form-control" id="state" name="state">
						</div>
						<div class="form-group col-lg-6">
							<label for="accolades">Accolades</label>
							<input type="text" value="<?php echo $wkMember->getAccolades() ?>" placeholder="Accolades..." class="form-control" id="accolades" name="accolades">
						</div>
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
