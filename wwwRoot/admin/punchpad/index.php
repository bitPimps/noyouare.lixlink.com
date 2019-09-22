<?php
include_once("../sec/config/phpConfig.php");
include_once("../sec/config/common_db.php");
include_once("../sec/phpinclude/classes/PunchPad.php");
include_once("../sec/phpinclude/classes/PunchPadSR.php");
(isset($_POST['searchCrit']) && $_POST['searchCrit'] != "") ? $searchCrit=htmlentities($_POST['searchCrit'], ENT_QUOTES, "UTF-8") : $searchCrit="";
(isset($_POST['strike']) && $_POST['strike'] != "") ? $strike=htmlentities($_POST['strike'], ENT_QUOTES, "UTF-8") : $strike="";
(isset($_POST['sortBy']) && $_POST['sortBy'] != "") ? $sortBy=htmlentities($_POST['sortBy'], ENT_QUOTES, "UTF-8") : $sortBy="id";
// Connect to Database
$dbConn = connectNYRDb();
// Range Variables
$MAX_NUM_ROWS = 5000;
$lowerBound = 0;
// Get PunchPadSR
$padSR = new PunchPadSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $searchCrit, $strike, $sortBy, $publish);
$punchpads = $padSR->getPunchPads();
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
		<title>NYA Administration &gt; TJES Punch-Pad Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php
	require_once("../inc/nav_main.php");
	DrawNavMain("PunchPad");
	?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="/admin/index.php">Home</a></li>
			<li class="active">TJES Punch-Pad Results</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>Show Only <small>Strike</small></h2>
				<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" name="punchSearch" class="form-inline" role="form">
					<div class="input-group">
						<select size="1" onChange="this.form.submit()" class="form-control" id="strike" name="strike">
							<option value=""<?php if($strike==""){ ?> selected<?php } ?>>Show All...</option>
							<option value="Punch"<?php if($strike=="Punch"){ ?> selected<?php } ?>>Punch</option>
							<option value="Knee"<?php if($strike=="Knee"){ ?> selected<?php } ?>>Knee</option>
						</select>
					</div><!-- /input-group -->
				</form>
				<p>&nbsp;</p>
				<div class="btn-group">
					<a href="controller.php" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <strong>Add a new result</strong></a>
				</div><!-- /btn-group -->
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8">
				<h1>TJES Punch-Pad <small>Database</small></h1>
				<p>Before posting a result, please:</p>
				<ol>
					<li>Search to make sure it doesn't already exist.</li>
					<li>Make sure you credit the result to the right person.</li>
				</ol>
				<?php if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != ""){ ?>
				<div class="alert alert-success" role="alert"><?php echo $_REQUEST['msg']; ?></div>
				<?php } ?>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
						<tr>
							<th>Name</th>
							<th>Strike</th>
							<th class="text-center">Score</th>
							<th class="text-center">Active</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i = 0; $i<count($punchpads); $i++){ ?>
						<tr>
							<td><a href="controller.php?id=<?php echo $punchpads[$i]->getId(); ?>"><?php echo $punchpads[$i]->getFirstName(); ?> <?php echo $punchpads[$i]->getLastName(); ?></a></td>
							<td><?php echo $punchpads[$i]->getStrike(); ?></td>
							<td class="text-center"><?php echo $punchpads[$i]->getScore(); ?></td>
							<td class="text-center"><span class="glyphicon <?php if ($punchpads[$i]->getPublish()==1){ ?>glyphicon-ok green<?php } else { ?>glyphicon-remove red<?php } ?>"></span></td>
						</tr>
						<?php } if(count($punchpads)==0){ ?>
						<tr>
							<td colspan="4" class="info">No results at this time...</td>
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
	</body>
</html>
