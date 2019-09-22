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
 Template Name: TJES Guests
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
include_once("admin/sec/config/phpConfig.php");
include_once("admin/sec/config/common_db.php");
include_once("admin/sec/phpinclude/classes/Guest.php");
include_once("admin/sec/phpinclude/classes/GuestSR.php");

// Connect to Database
$dbConn = connectNYRDb();

// Range Variables
$MAX_NUM_ROWS = 1500;
$lowerBound = 0;

// Get GuestSR
$guestSR = new GuestSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, $sortBy, "1");
$guests = $guestSR->getGuests();
$totalNum = $guestSR->getTotalNum();

// Close DB Connection
mysqli_close($dbConn);
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">TJES Guests</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<p>Memorable and notable guests who have been on The Jason Ellis Show.</p>
		<p>Some guests and cast members hit the punch-pad, be sure to check out their <a href="http://noyouare.lixlink.com/tjes/tjes-punch-pad-results/">Punch-Pad Results</a>!</p>
		<p>Want to see a list of past Jason Ellis Show cast and crew members? Check out our <a href="http://noyouare.lixlink.com/tjes/tjes-interns/">TJES Interns</a> page, which includes present and past crew members.</p>
		<p>There are also plenty of times the main cast and crew say something hilarious or crazy, you can read some of those on our <a href="http://noyouare.lixlink.com/tjes/tjes-quotes/">TJES Quotes</a> page!</p>
		<p>Total Guests Entered So Far: <?php echo $totalNum ?></p>

<?php
// Put results into an array
for ($z = 0; $z<count($guests); $z++) {
	$arrRecs[$z] = $guests[$z]->getName();
}
$lastChar = "";
sort($arrRecs, SORT_STRING | SORT_FLAG_CASE);
// Echo results
foreach($arrRecs as $val) {
	$char = $val[0]; //first char
	if ($char !== $lastChar) {
		if ($lastChar !== '')
			echo '<a href="#top" style="float:right;">Back to top</a><hr>';
			
			echo '<strong>'.strtoupper($char).'</strong><br>'; //print A / B / C etc
			$lastChar = $char;
	}
	echo $val.'<br>';
}
?>
<p>&nbsp;</p>
			</div><!-- .entry-content -->
	<footer class="entry-meta">
			</footer><!-- .entry-meta -->
</article><!-- #post-5580 -->

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>

