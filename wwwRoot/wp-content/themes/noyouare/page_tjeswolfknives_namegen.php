<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage No_You_Are
 * @since No You Are 1.0
 *
 Template Name: TJES WK Name Gen
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
include_once("admin/sec/config/phpConfig.php");
include_once("admin/sec/config/common_db.php");
include_once("admin/sec/phpinclude/classes/Wolfknive.php");
include_once("admin/sec/phpinclude/classes/WolfkniveSR.php");

// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 1;
$lowerBound = 0;

// Get WolfkniveSR
$wolfkniveSR = new WolfkniveSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, "RAND()", "1");
$wolfknives = $wolfkniveSR->getWolfknives();

// Close DB Connection
mysqli_close($dbConn);
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">Wolfknives Random Name Generator</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<p>This is just for fun. We don't give out Wolfknives names, only Ellis does. But what if we did?</p>
		<p>Want to see a list of some real Wolfknives members? Check out the <a href="http://noyouare.lixlink.com/tjes/wolfknives-membership-registry/">Wolfknives Membership Registry</a>!</p>
		<!--<p>Enter your real first name, select your gender, and click &quot;SUBMIT&quot; to get your fake Wolfknives name.</p>-->
		<?php //if($wasSent=="") { ?>
		<!--
		<form action="/tjes/wk-name-gen/" method="post" name="nameGen">
			<fieldset class="ui-corner-all">
				<legend>What is your first name &amp; gender?</legend>
				<input type="hidden" name="hereVar" value="true">
				<label for="fName">First Name:</label>
				<input type="text" name="fName" id="fName" value=""><br>
				<label for="gender" id="gender">Gender:</label>
				<select name="gender" id="gender">
					<option>Select a gender...</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
				</select><br>
				<input type="submit" name="submit" value="SUBMIT" class="button ui-corner-all">
			</fieldset>
		</form>
		-->
		<?php //} else { ?>
		<h2>Congratulations!</h2>
		<p>You are now known as:</p>
		<p><strong><?php for ($i = 0; $i<count($wolfknives); $i++){ ?><?php echo $wolfknives[$i]->getName(); ?><?php } if(count($wolfknives)==0){ ?>Fuckity Fuck-Fuck<?php } ?></strong></p>
		<?php //} ?>

<p>&nbsp;</p>
			</div><!-- .entry-content -->
	<footer class="entry-meta">
			</footer><!-- .entry-meta -->
</article><!-- #post-5580 -->

			</div><!-- #content -->
		</div><!-- #primary -->
		<script type="text/javascript">
		$(document).ready(function() {
			$("button, input:submit, a", ".btn").button();
			$("a", ".btn").click(function() {return true;});
		});
		</script>
<?php get_footer(); ?>

