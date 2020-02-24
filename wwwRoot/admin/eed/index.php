<?php
include_once("../sec/config/phpConfig.php");
include_once("../sec/config/common_db.php");
include_once("../sec/phpinclude/classes/Dict.php");
include_once("../sec/phpinclude/classes/DictSR.php");
(isset($_POST['sName']) && $_POST['sName'] != "") ? $sName=htmlentities($_POST['sName'], ENT_QUOTES, "UTF-8") : $sName="";
// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 5000;
$lowerBound = 0;

// Get DictSR
$dictSR = new DictSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, $sortBy, $publish);
$ellisWords = $dictSR->getDicts();

// Close DB Connection
mysqli_close($dbConn);
?>
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
			<li class="breadcrumb-item active">Ellis English Dictionary</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
				<h2>&nbsp;</h2>
				<p><a href="controller.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-plus-circle"></i> Add New Term</a></p>
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8 main">
				<h1>Ellis English Dictionary <small>Database</small></h1>
				<p>A list of Ellis words &amp; their meanings.</p>
				<p>Before posting a Term, please:</p>
				<ol>
					<li>Search to make sure it doesn't already exist.</li>
					<li>Make sure the data you enter is correct.</li>
					<li>A Term &amp; Definition are both required.</li>
				</ol>
				<?php if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != ""){ ?>
				<div class="alert alert-success" role="alert"><?php echo $_REQUEST['msg']; ?></div>
				<?php } ?>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
						<tr>
							<th>What Ellis Says</th>
							<th>What Ellis Means</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i = 0; $i<count($ellisWords); $i++){ ?>
						<tr>
							<td><a href="controller.php?id=<?php echo $ellisWords[$i]->getId(); ?>"><?php echo $ellisWords[$i]->getTerm(); ?></a></td>
							<td><?php echo $ellisWords[$i]->getDefinition(); ?></td>
						</tr>
						<?php } if(count($ellisWords)==0){ ?>
						<tr>
							<td colspan="2" class="info">No terms at this time...</td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div><!-- /table-responsive -->
			</div><!-- /.col-sm-8 -->
		</div><!-- /.row -->
	</main><!-- /container -->
	<?php include_once("../inc/footer.php"); ?>
	</body>
</html>