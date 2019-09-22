<?php
include_once("../sec/config/phpConfig.php");
include_once("../sec/config/common_db.php");
include_once("../sec/phpinclude/classes/WolfknivesMember.php");
include_once("../sec/phpinclude/classes/WolfknivesMemberSR.php");
(isset($_REQUEST['sortBy']) && $_REQUEST['sortBy'] != "") ? $sortBy=htmlentities($_REQUEST['sortBy'], ENT_QUOTES, "UTF-8") : $sortBy="";
// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 5000;
$lowerBound = 0;

// Get WolfknivesMemberSR
$wolfknivesMemberSR = new WolfknivesMemberSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $searchCrit, $sortBy, $publish);
$wolfknivesMembers = $wolfknivesMemberSR->getWolfknivesMembers();

// Close DB Connection
mysqli_close($dbConn);
?>
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
			<li class="active">Wolfknives Member Registry</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>Show Only <small>Author</small></h2>
				<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" name="wkMemberSearch" class="form-inline" role="form">
					<div class="input-group">
						<select size="1" onChange="this.form.submit()" class="form-control" id="author" name="author">
							<option value="nameWK"<?php if($sortBy=="" || $sortBy=="nameWK"){ ?> selected<?php } ?>>Wolfknives Name</option>
							<option value="firstNameReal"<?php if($sortBy=="firstNameReal"){ ?> selected<?php } ?>>First Name</option>
							<option value="nameTwitter"<?php if($sortBy=="nameTwitter"){ ?> selected<?php } ?>>Twitter Name</option>
							<option value="nameInstagram"<?php if($sortBy=="nameInstagram"){ ?> selected<?php } ?>>Instagram Name</option>
							<option value="nameOJE"<?php if($sortBy=="nameOJE"){ ?> selected<?php } ?>>Official Jason Ellis.com Name</option>
							<option value="state"<?php if($sortBy=="state"){ ?> selected<?php } ?>>State</option>
							<option value="rank"<?php if($sortBy=="rank"){ ?> selected<?php } ?>>Rank</option>
						</select>
					</div><!-- /input-group -->
				</form>
				<p>&nbsp;</p>
				<div class="btn-group">
					<a href="controller.php" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <strong>Add a new Wolfknives Member</strong></a>
				</div><!-- /btn-group -->
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8">
				<h1>Wolfknives Member Registry <small>Database</small></h1>
				<p>Before posting a Wolfknives Member, please:</p>
				<ol>
					<li>Search to make sure it doesn't already exist.</li>
					<li>Make sure the data you enter is correct.</li>
					<li>Wolfnives Name &amp; Real Name are required.</li>
				</ol>
				<?php if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != ""){ ?>
				<div class="alert alert-success" role="alert"><?php echo $_REQUEST['msg']; ?></div>
				<?php } ?>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-responsive table-striped">
						<thead>
						<tr>
							<th>WK Name</th>
							<th>Name</th>
							<th>State</th>
							<th class="text-center"><img src="../../wp-content/themes/noyouare/images/icons/twitter.png" border="0"></th>
							<th class="text-center"><img src="../../wp-content/themes/noyouare/images/icons/instagram.png" border="0"></th>
							<th class="text-center"><img src="../../wp-content/themes/noyouare/images/icons/officialjasonellis.png" border="0"></th>
							<th>Rank</th>
							<th class="text-center">Active</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i = 0; $i<count($wolfknivesMembers); $i++){ ?>
						<tr>
							<td><a href="controller.php?id=<?php echo $wolfknivesMembers[$i]->getId(); ?>"><?php echo $wolfknivesMembers[$i]->getNameWK(); ?></a></td>
							<td><?php echo $wolfknivesMembers[$i]->getFirstNameReal(); ?><?php if($wolfknivesMembers[$i]->getLastNameReal()!=""){ ?> <?php echo $wolfknivesMembers[$i]->getLastNameReal(); ?><?php } ?></td>
							<td><?php echo $wolfknivesMembers[$i]->getState(); ?></td>
							<td class="text-center"><?php if ($wolfknivesMembers[$i]->getNameTwitter() != "") { ?><a href="https://twitter.com/<?php echo $wolfknivesMembers[$i]->getNameTwitter(); ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="@<?php echo $wolfknivesMembers[$i]->getNameTwitter(); ?>"><img src="../../wp-content/themes/noyouare/images/icons/twitter.png" border="0"></a><?php } else { ?>&nbsp;<?php } ?></td>
							<td class="text-center"><?php if ($wolfknivesMembers[$i]->getNameInstagram() != "") { ?><a href="http://instagram.com/<?php echo $wolfknivesMembers[$i]->getNameInstagram(); ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="@<?php echo $wolfknivesMembers[$i]->getNameInstagram(); ?>"><img src="../../wp-content/themes/noyouare/images/icons/instagram.png" border="0"></a><?php } else { ?>&nbsp;<?php } ?></td>
							<td class="text-center"><?php if ($wolfknivesMembers[$i]->getNameOJE() != "") { ?><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $wolfknivesMembers[$i]->getNameOJE(); ?>"><img src="../../wp-content/themes/noyouare/images/icons/officialjasonellis.png" border="0"></a><?php } else { ?>&nbsp;<?php } ?></td>
							<td><?php echo $wolfknivesMembers[$i]->getRank(); ?></td>
							<td class="text-center"><span class="glyphicon <?php if ($wolfknivesMembers[$i]->getPublish()==1){ ?>glyphicon-ok green<?php } else { ?>glyphicon-remove red<?php } ?>"></span></td>
						</tr>
						<?php } if(count($wolfknivesMembers)==0){ ?>
						<tr>
							<td colspan="8" class="info">No Wolfknives Members at this time...</td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div><!-- /table-responsive -->
			</div><!-- /.col-sm-8 -->
		</div><!-- /.row -->
		<?php require_once("../inc/footer.php"); ?>
	</div><!-- /container -->
	<?php require_once("../inc/footer_scripts.php"); ?>
	<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip({
			'placement': 'top'
		});
	});
	</script>
	</body>
</html>
