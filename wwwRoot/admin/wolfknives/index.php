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
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>NYA Administration &gt; TJES Wolfknives Names Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php require_once("../inc/nav_main.php"); DrawNavMain("Wolfknives Names"); ?>
	<main role="main" class="container">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/admin/index.php">Home</a></li>
			<li class="breadcrumb-item active">TJES Wolfknives Names</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
				<h2>Wolfknives <small>Search</small></h2>
				<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" name="wolfSearch" class="form-inline" role="form">
					<div class="input-group">
						<input type="text" value="<?php echo $sName ?>" placeholder="Wolfknives Search..." name="sName" id="sName" class="form-control">
						<div class="input-group-prepend">
							<button class="btn btn-info" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</div><!-- /input-group -->
				</form>
				<p>&nbsp;</p>
				<p><a href="controller.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-plus-circle"></i> Add New Name</a></p>
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8 main">
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
							<td class="text-center"><?php if ($wolfknives[$i]->getPublish()==1){ ?><i class="fa fa-check-circle green" aria-hidden="true"></i><?php } else { ?><i class="fa fa-ban red" aria-hidden="true"></i><?php } ?></td>
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
	</div><!-- /container -->
	<?php require_once("../inc/footer.php"); ?>
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
