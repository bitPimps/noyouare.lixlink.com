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
			<li class="breadcrumb-item active">TJES Quotes</li>
		</ol><!-- /breadcrumb -->
		<div class="row">
			<div class="col-sm-3 col-sm-offset-1 sidebar">
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
				<p><a href="controller.php" class="btn btn-lg btn-success" role="button"><i class="fa fa-plus-circle"></i> Add New Quote</a></p>
			</div><!-- /.col-sm-3 col-sm-offset-1 -->
			<div class="col-sm-8 main">
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
							<td class="text-center"><?php if ($quotes[$i]->getPublish()==1){ ?><i class="fa fa-check-circle green" aria-hidden="true"></i><?php } else { ?><i class="fa fa-ban red" aria-hidden="true"></i><?php } ?></td>
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
	</main><!-- /container -->
	<?php require_once("../inc/footer.php"); ?>
	</body>
</html>
