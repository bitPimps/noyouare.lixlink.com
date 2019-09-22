<?php
include_once("../sec/config/phpConfig.php");
include_once("../sec/config/common_db.php");
include_once("../sec/phpinclude/classes/Quote.php");
include_once("../sec/phpinclude/classes/QuoteSR.php");
(isset($_POST['author']) && $_POST['author'] != "") ? $author=htmlentities($_POST['author'], ENT_QUOTES, "UTF-8") : $author="";
(isset($_POST['sortBy']) && $_POST['sortBy'] != "") ? $sortBy=htmlentities($_POST['sortBy'], ENT_QUOTES, "UTF-8") : $sortBy="id";
// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 5000;
$lowerBound = 0;

// Get QuoteSR
$quoteSR = new QuoteSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $author, $sortBy, $publish);
$quotes = $quoteSR->getQuotes();

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
			<li class="active">TJES Quotes</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1">
				<h2>Show Only <small>Author</small></h2>
				<form action="<?php echo basename($_SERVER['PHP_SELF']) ?>" method="post" name="quoteSearch" class="form-inline" role="form">
					<div class="input-group">
						<select size="1" onChange="this.form.submit()" class="form-control" id="author" name="author">
							<option value=""<?php if($author==""){ ?> selected<?php } ?>>Show All...</option>
							<option value="Jason Ellis"<?php if($author=="Jason Ellis"){ ?> selected<?php } ?>>Jason Ellis</option>
							<option value="Micheal Tully"<?php if($author=="Micheal Tully"){ ?> selected<?php } ?>>Micheal Tully</option>
							<option value="Josh Richmond"<?php if($author=="Josh Richmond"){ ?> selected<?php } ?>>Josh &quot;Rawdog&quot; Richmond</option>
							<option value="Will Pendarvis"<?php if($author=="Will Pendarvis"){ ?> selected<?php } ?>>Will &quot;Shiny Shins&quot; Pendarvis</option>
							<option value="Kevin Kraft"<?php if($author=="Kevin Kraft"){ ?> selected<?php } ?>>Kevin &quot;Cumtard&quot; Kraft</option>
						</select>
					</div><!-- /input-group -->
				</form>
				<p>&nbsp;</p>
				<div class="btn-group">
					<a href="controller.php" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <strong>Add a new quote</strong></a>
				</div><!-- /btn-group -->
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8">
				<h1>TJES Quote <small>Database</small></h1>
				<p>A list of guests on TJES.</p>
				<p>Before posting a quote, please:</p>
				<ol>
					<li>Search to make sure it doesn't already exist.</li>
					<li>Make sure you credit the quote to the right person.</li>
					<li>Quotes and Contexts should only be 1 sentence and not over 1,000 characters.</li>
				</ol>
				<?php if (isset($_REQUEST['msg']) && $_REQUEST['msg'] != ""){ ?>
				<div class="alert alert-success" role="alert"><?php echo $_REQUEST['msg']; ?></div>
				<?php } ?>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
						<tr>
							<th>Quote</th>
							<th>Author</th>
							<th class="text-center">Active</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i = 0; $i<count($quotes); $i++){ ?>
						<tr>
							<td><a href="controller.php?id=<?php echo $quotes[$i]->getId(); ?>">&quot;<?php echo $quotes[$i]->getBody(); ?>&quot;</a></td>
							<td><?php echo $quotes[$i]->getAuthor(); ?></td>
							<td class="text-center"><span class="glyphicon <?php if ($quotes[$i]->getPublish()==1){ ?>glyphicon-ok green<?php } else { ?>glyphicon-remove red<?php } ?>"></span></td>
						</tr>
						<?php } if(count($quotes)==0){ ?>
						<tr>
							<td colspan="3" class="info">No quotes at this time...</td>
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
