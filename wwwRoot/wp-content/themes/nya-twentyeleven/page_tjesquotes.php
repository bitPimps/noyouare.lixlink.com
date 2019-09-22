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
 Template Name: TJES Quotes
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
include("admin/sec/config/phpConfig.php");
include("admin/sec/config/common_db.php");
include("admin/sec/phpinclude/classes/Quote.php");
include("admin/sec/phpinclude/classes/QuoteSR.php");

// Connect to Database
$dbConn = connectNYRDb($dbHost, $dbUsername, $dbPassword);

// Range Variables
$MAX_NUM_ROWS = 1500;
$lowerBound = 0;

// Get QuoteSR (Ellis)
$quoteSR = new QuoteSR($dbConn, $lowerBound, $MAX_NUM_ROWS, "Jason Ellis", "id", "1");
$quotesEllis = $quoteSR->getQuotes();

// Get QuoteSR (Tully)
$quoteSR = new QuoteSR($dbConn, $lowerBound, $MAX_NUM_ROWS, "Micheal Tully", "id", "1");
$quotesTully = $quoteSR->getQuotes();

// Get QuoteSR (Rawdog)
$quoteSR = new QuoteSR($dbConn, $lowerBound, $MAX_NUM_ROWS, "Josh Richmond", "id", "1");
$quotesRawdog = $quoteSR->getQuotes();

// Get QuoteSR (Pendarvis)
$quoteSR = new QuoteSR($dbConn, $lowerBound, $MAX_NUM_ROWS, "Will Pendarvis", "id", "1");
$quotesPendarvis = $quoteSR->getQuotes();

// Get QuoteSR (Kraft)
//$quoteSR = new QuoteSR($dbConn, $lowerBound, $MAX_NUM_ROWS, "Kevin Kraft", "id", "1");
//$quotesKraft = $quoteSR->getQuotes();

// Close DB Connection
mysql_close($dbConn);
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">TJES Quotes</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<p>Memorable quotes from the cast of The Jason Ellis Show.</p>
		<p>Want to see a list of past Jason Ellis Show cast and crew members? Check out our <a href="http://noyouare.lixlink.com/tjes/tjes-interns/">TJES Interns</a> page, which includes present and past crew members.</p>
		<p>Some guests and cast members hit the punch-pad, be sure to check out their <a href="http://noyouare.lixlink.com/tjes/tjes-punch-pad-results/">Punch-Pad Results</a>!</p>
		<p>Want see a list of many of the guests that have appeard on the show? Check out our <a href="http://noyouare.lixlink.com/tjes/tjes-guests/">TJES Guests</a> page!</p>
		<div id="accordion">
			<h3>Jason Ellis</h3>
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblEllis">
					<?php for ($ellis = 0; $ellis<count($quotesEllis); $ellis++){ ?>
					<tr>
						<td>
							<em>&quot;<?php echo $quotesEllis[$ellis]->getBody(); ?>&quot;</em><br>
							<span class="context">[<?php echo $quotesEllis[$ellis]->getContext(); ?>]</span>
						</td>
					</tr>
					<?php } if(count($quotesEllis)==0){ ?>
					<tr>
						<td>No quotes from Jason Ellis at this time...</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<h3>Michael Tully</h3>
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblTully">
					<?php for ($tully = 0; $tully<count($quotesTully); $tully++){ ?>
					<tr>
						<td>
							<em>&quot;<?php echo $quotesTully[$tully]->getBody(); ?>&quot;</em><br>
							<span class="context">[<?php echo $quotesTully[$tully]->getContext(); ?>]</span>
						</td>
					</tr>
					<?php } if(count($quotesTully)==0){ ?>
					<tr>
						<td>No quotes from Michael Tully at this time...</td>
					</tr>
					<?php } ?>
				</table>
				<a class="twitter-timeline" data-chrome="nofooter" data-dnt="true" href="https://twitter.com/TullySpeaks"  data-widget-id="472102078769139712">Tweets by @TullySpeaks</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				<a class="twitter-timeline" data-chrome="nofooter" data-dnt="true" href="https://twitter.com/StuffPTullySays"  data-widget-id="472104274764111872">Tweets by @StuffPTullySays</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<h3>Will &quot;Shiny Shins&quot; Pendarvis</h3>
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblPendarvis">
					<?php for ($pendarvis = 0; $pendarvis<count($quotesPendarvis); $pendarvis++){ ?>
					<tr>
						<td>
							<em>&quot;<?php echo $quotesPendarvis[$pendarvis]->getBody(); ?>&quot;</em><br>
							<span class="context">[<?php echo $quotesPendarvis[$pendarvis]->getContext(); ?>]</span>
						</td>
					</tr>
					<?php } if(count($quotesPendarvis)==0){ ?>
					<tr>
						<td>No quotes from Will &quot;Shiny Shins&quot; Pendarvis at this time...</td>
					</tr>
					<?php } ?>
				</table>
				<a class="twitter-timeline" data-chrome="nofooter" data-dnt="true" href="https://twitter.com/PendarvisSpeaks" data-widget-id="499695251867455489">Tweets by @PendarvisSpeaks</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<!--<h3>Kevin &quot;Cumtard&quot; Kraft</h3>
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblKraft">
					<?php //for ($kraft = 0; $kraft<count($quotesKraft); $kraft++){ ?>
					<tr>
						<td>
							<em>&quot;<?php //echo $quotesKraft[$kraft]->getBody(); ?>&quot;</em><br>
							<span class="context">[<?php //echo $quotesKraft[$kraft]->getContext(); ?>]</span>
						</td>
					</tr>
					<?php //} if(count($quotesKraft)==0){ ?>
					<tr>
						<td>No quotes from Kevin &quot;Cumtard&quot; Kraft at this time...</td>
					</tr>
					<?php //} ?>
				</table>
				<a class="twitter-timeline" data-chrome="nofooter" data-dnt="true" href="https://twitter.com/CumTardSpeaks"  data-widget-id="472102481502011392">Tweets by @CumTardSpeaks</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			-->
			<h3>Josh &quot;Rawdog&quot; Richmond</h3>
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblRawdog">
					<?php for ($rawdog = 0; $rawdog<count($quotesRawdog); $rawdog++){ ?>
					<tr>
						<td>
							<em>&quot;<?php echo $quotesRawdog[$rawdog]->getBody(); ?>&quot;</em><br>
							<span class="context">[<?php echo $quotesRawdog[$rawdog]->getContext(); ?>]</span>
						</td>
					</tr>
					<?php } if(count($quotesRawdog)==0){ ?>
					<tr>
						<td>No quotes from Josh &quot;Rawdog&quot; Richmond at this time...</td>
					</tr>
					<?php } ?>
				</table>
				<a class="twitter-timeline" data-chrome="nofooter" data-dnt="true" href="https://twitter.com/rawdogsaidit"  data-widget-id="472104636015329280">Tweets by @rawdogsaidit</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</div>

		<h3>Share and Enjoy</h3>

<!-- Start WP Socializer - Social Buttons - Output -->
<div class="wp-socializer 32px">
<ul class="wp-socializer-opacity columns-no">
 <li><a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-quotes%2F&amp;t=TJES+Quotes" title="Share this on Facebook" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Facebook" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -330px; border:0;"/></a></li> 

 <li><a href="http://twitter.com/home?status=TJES+Quotes%20-%20http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-quotes%2F%20" title="Tweet this !" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Twitter" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1386px; border:0;"/></a></li> 

 <li><a href="http://delicious.com/post?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-quotes%2F&amp;title=TJES+Quotes&amp;notes=Memorable+quotes+from+the+cast+of+The+Jason+Ellis+Show." title="Post this on Delicious" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Delicious" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -132px; border:0;"/></a></li> 

 <li><a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Ftjes-quotes%2F&amp;title=TJES+Quotes" title="Submit this to StumbleUpon" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="StumbleUpon" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1287px; border:0;"/></a></li> 

 <li><a href="http://noyouare.lixlink.com/tjes/tjes-quotes/" onclick="addBookmark(event);" title="Add to favorites" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Add to favorites" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -0px; border:0;"/></a></li> 

 <li><a href="mailto:?to=&amp;subject=TJES+Quotes&amp;body=Memorable+quotes+from+the+cast+of+The+Jason+Ellis+Show.%20-%20http://noyouare.lixlink.com/tjes/tjes-quotes/" title="Email this" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Email" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -297px; border:0;"/></a></li> 

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