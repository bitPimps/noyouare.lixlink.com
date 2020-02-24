<?php
require_once("../sec/config/phpConfig.php");
require_once("../sec/config/common_db.php");
require_once("../sec/phpinclude/classes/Guest.php");
require_once("../sec/phpinclude/classes/GuestSR.php");
(isset($_POST['sName']) && $_POST['sName'] != "") ? $sName=htmlentities($_POST['sName'], ENT_QUOTES, "UTF-8") : $sName="";
// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 5000;
$lowerBound = 0;

// Get GuestSR
$guestSR = new GuestSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, $sortBy, $publish);
$guests = $guestSR->getGuests();

// Close DB Connection
mysqli_close($dbConn);
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>NYA Administration &gt; TJES Guest Database</title>
		<?php include_once("../inc/meta-data.php"); ?>
	</head>
	<body>
	<?php require_once("../inc/nav_main.php"); DrawNavMain("Guests"); ?>
	<main role="main" class="container">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/admin/index.php">Home</a></li>
			<li class="breadcrumb-item active">TJES Guests</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
				<h2>Search <small>Guests</small></h2>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="guestSearch" class="form-inline" role="form">
					<div class="input-group">
						<input type="text" value="<?php echo $sName ?>" placeholder="Guest Search..." name="sName" id="sName" class="form-control">
						<div class="input-group-prepend">
							<button class="btn btn-info" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</div><!-- /input-group -->
				</form>
				<p>&nbsp;</p>
				<p><a href="controller.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-plus-circle"></i> Add New Guest</a></p>
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8 main">
				<h1>TJES Guest <small>Database</small></h1>
				<p>A list of guests on TJES.</p>
				<p>Before posting a guest, please:</p>
				<ol>
					<li>Search to make sure it doesn't already exist.</li>
					<li>Make sure you spell their name correctly.</li>
					<li>Link to their official Twitter. If they don't have one, link to their Wikipedia page.</li>
				</ol>
				<?php if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != ""){ ?>
				<div class="alert alert-success" role="alert"><?php echo $_REQUEST['msg']; ?></div>
				<?php } ?>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
						<tr>
							<th>Guest</th>
							<th class="text-center">Active</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i = 0; $i<count($guests); $i++){ ?>
						<tr>
							<td><a href="controller.php?id=<?php echo $guests[$i]->getId(); ?>"><?php echo $guests[$i]->getName(); ?></a></td>
							<td class="text-center"><?php if ($guests[$i]->getPublish()==1){ ?><i class="fa fa-check-circle green" aria-hidden="true"></i><?php } else { ?><i class="fa fa-ban red" aria-hidden="true"></i><?php } ?></td>
						</tr>
						<?php } if(count($guests)==0){ ?>
						<tr>
							<td colspan="2" class="info">No guests at this time...</td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div><!-- /table-responsive -->
			</div><!-- /.col-sm-8 -->
		</div><!-- /.row -->
	</main><!-- /container -->
	<?php include_once("../inc/footer.php"); ?>
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
