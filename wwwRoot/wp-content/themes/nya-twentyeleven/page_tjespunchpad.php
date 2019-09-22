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
 Template Name: TJES Punch-Pad Results
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
include("admin/sec/config/phpConfig.php");
include("admin/sec/config/common_db.php");
include("admin/sec/phpinclude/classes/PunchPad.php");
include("admin/sec/phpinclude/classes/PunchPadSR.php");
// Connect to Database
$dbConn = connectNYRDb($dbHost, $dbUsername, $dbPassword);
// Range Variables
$MAX_NUM_ROWS = 1500;
$lowerBound = 0;
// Get PunchPadSR (Punch)
$padSR = new PunchPadSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $searchCrit, "Punch", $sortBy, "1");
$padPunch = $padSR->getPunchPads();
// Get PunchPadSR (Knee)
$padSR = new PunchPadSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $searchCrit, "Knee", $sortBy, "1");
$padKnee = $padSR->getPunchPads();
// Close DB Connection
mysql_close($dbConn);
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">TJES Punch-Pad Results</h1>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<p>Rankings of guests who have hit the punch-pad.</p>
		<p>Want see a list of many of the guests that have appeard on the show? Check out our <a href="http://noyouare.lixlink.com/tjes/tjes-guests/">TJES Guests</a> page!</p>
		<p>Want to see a list of past Jason Ellis Show cast and crew members? Check out our <a href="http://noyouare.lixlink.com/tjes/tjes-interns/">TJES Interns</a> page, which includes present and past crew members.</p>
		<p>There are also plenty of times the main cast and crew say something hilarious or crazy, you can read some of those on our <a href="http://noyouare.lixlink.com/tjes/tjes-quotes/">TJES Quotes</a> page!</p>
		<h1>Punches</h1>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblPunches">
			<thead>
			<tr>
				<th><div style="font-size:14px; font-weight:bold;">First</div></th>
				<th><div style="font-size:14px; font-weight:bold;">Last</div></th>
				<th><div style="text-align:center; font-size:14px; font-weight:bold;">Score</div></th>
			</tr>
			</thead>
			<tbody>
			<?php for ($punch = 0; $punch<count($padPunch); $punch++){ ?>
			<tr>
				<td><?php echo $padPunch[$punch]->getFirstName(); ?></td>
				<td><?php echo $padPunch[$punch]->getLastName(); ?></td>
				<td><div style="text-align:center;"><?php echo $padPunch[$punch]->getScore(); ?></div></td>
			</tr>
			<?php } if(count($padPunch)==0){ ?>
			<tr>
				<td colspan="3">No punches have been recorded...</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		<hr />
		<h1>Knees</h1>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblKnees">
			<thead>
			<tr>
				<th><div style="font-size:14px; font-weight:bold;">First</div></th>
				<th><div style="font-size:14px; font-weight:bold;">Last</div></th>
				<th><div style="text-align:center; font-size:14px; font-weight:bold;">Score</div></th>
			</tr>
			</thead>
			<tbody>
			<?php for ($knee = 0; $knee<count($padKnee); $knee++){ ?>
			<tr>
				<td><?php echo $padKnee[$knee]->getFirstName(); ?></td>
				<td><?php echo $padKnee[$knee]->getLastName(); ?></td>
				<td><div style="text-align:center;"><?php echo $padKnee[$knee]->getScore(); ?></div></td>
			</tr>
			<?php } if(count($padKnee)==0){ ?>
			<tr>
				<td colspan="3">No knees have been recorded...</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		<h3>Share and Enjoy</h3>

<!-- Start WP Socializer - Social Buttons - Output -->
<div class="wp-socializer 32px">
<ul class="wp-socializer-opacity columns-no">
 <li><a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-punch-pad-results%2F&amp;t=TJES+Punch-Pad+Results" title="Share this on Facebook" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Facebook" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -330px; border:0;"/></a></li> 

 <li><a href="http://twitter.com/home?status=TJES+Punch-Pad+Results%20-%20http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-punch-pad-results%2F%20" title="Tweet this !" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Twitter" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1386px; border:0;"/></a></li> 

 <li><a href="http://delicious.com/post?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-punch-pad-results%2F&amp;title=TJES+Quotes&amp;notes=TJES+Punch-Pad+Results" title="Post this on Delicious" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Delicious" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -132px; border:0;"/></a></li> 

 <li><a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-punch-pad-results%2F&amp;title=TJES+Punch-Pad+Results" title="Submit this to StumbleUpon" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="StumbleUpon" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1287px; border:0;"/></a></li> 

 <li><a href="http://noyouare.lixlink.com/tjes/tjes-punch-pad-results/" onclick="addBookmark(event);" title="Add to favorites" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Add to favorites" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -0px; border:0;"/></a></li> 

 <li><a href="mailto:?to=&amp;subject=TJES+Quotes&amp;body=TJES+Punch-Pad+Results%20-%20http://noyouare.lixlink.com/tjes/tjes-punch-pad-results/" title="Email this" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Email" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -297px; border:0;"/></a></li> 

 <li><a href="http://noyouare.lixlink.com/feed/rss/" title="Subscribe to RSS" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="RSS" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1221px; border:0;"/></a></li> 
</ul> 
<div class="wp-socializer-clearer"></div></div>
<!-- End WP Socializer - Social Buttons - Output -->
			</div><!-- .entry-content -->
	<footer class="entry-meta">
			</footer><!-- .entry-meta -->
</article><!-- #post-5580 -->

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>

<script>
$(document).ready(function() {
	$( "#accordion" ).accordion({
		active: false,
		collapsible: true,
		heightStyle: "content"
	});
	$(".stripeMe tr").mouseover(function(){$(this).addClass("over");}).mouseout(function(){$(this).removeClass("over");});
	$(".stripeMe tr:nth-child(even)").addClass("alt");
});
</script>