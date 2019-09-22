<?php
include_once("../sec/config/phpConfig.php");
include_once("../sec/config/common_db.php");
include_once("../sec/phpinclude/classes/Wolfknive.php");
include_once("../sec/phpinclude/classes/WolfkniveSR.php");
(isset($_POST['sName']) && $_POST['sName'] != "") ? $sName=htmlentities($_POST['sName'], ENT_QUOTES, "UTF-8") : $sName="";
// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 5000;
$lowerBound = 0;

// Get WolfkniveSR
$wolfkniveSR = new WolfkniveSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, $sortBy, $publish);
$wolfknives = $wolfkniveSR->getWolfknives();

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
		<title>NYA Administration &gt; TJES Wolfknives Names Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php
	require_once("../inc/nav_main.php");
	DrawNavMain("Wolfknives Names");
	?>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="/admin/index.php">Home</a></li>
			<li class="active">TJES Wolfknives Names</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>Wolfknives <small>Search</small></h2>
				<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" name="wolfSearch" class="form-inline" role="form">
					<div class="input-group">
						<input type="text" value="<?php echo $sName ?>" placeholder="Wolfknives Search..." name="sName" id="sName" class="form-control">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search"></span></button>
						</span>
					</div><!-- /input-group -->
				</form>
				<p>&nbsp;</p>
				<div class="btn-group">
					<a href="controller.php" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <strong>Add a new Wolfknives name</strong></a>
				</div><!-- /btn-group -->
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8">
				<h1>TJES Wolfknives Name <small>Database</small></h1>
				<p>Before posting a made-up Wolfknives name, please:</p>
				<ol>
					<li>Search to make sure it doesn't already exist.</li>
				</ol>
				<?php if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != ""){ ?>
				<div class="alert alert-success" role="alert"><?php echo $_REQUEST['msg']; ?></div>
				<?php } ?>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
						<tr>
							<th>Name</th>
							<th>Gender</th>
							<th class="text-center">Active</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i = 0; $i<count($wolfknives); $i++){ ?>
						<tr>
							<td><a href="controller.php?id=<?php echo $wolfknives[$i]->getId(); ?>"><?php echo $wolfknives[$i]->getName(); ?></a></td>
							<td><?php echo $wolfknives[$i]->getSex(); ?></td>
							<td class="text-center"><span class="glyphicon <?php if ($wolfknives[$i]->getPublish()==1){ ?>glyphicon-ok green<?php } else { ?>glyphicon-remove red<?php } ?>"></span></td>
						</tr>
						<?php } if(count($wolfknives)==0){ ?>
						<tr>
							<td colspan="3" class="info">No Wolfknives names at this time...</td>
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
	<script type="text/javascript">
		$(document).ready(function() {
			var cache = {},
			lastXhr;
			$( "#sName" ).autocomplete({
				minLength: 2,
				source: function( request, response ) {
					var term = request.term;
					if ( term in cache ) {
						response( cache[ term ] );
						return;
					}
					lastXhr = $.getJSON( "search.php", request, function( data, status, xhr ) {
						cache[ term ] = data;
						if ( xhr === lastXhr ) {
							response( data );
						}
					});
				}
			});
		});
	</script>
	</body>
</html>
