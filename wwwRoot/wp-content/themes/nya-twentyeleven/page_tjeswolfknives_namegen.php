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
$dbConn = connectNYRDb($dbHost, $dbUsername, $dbPassword);

// Range Variables
$MAX_NUM_ROWS = 1;
$lowerBound = 0;

// Get WolfkniveSR
$wolfkniveSR = new WolfkniveSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, "RAND()", "1");
$wolfknives = $wolfkniveSR->getWolfknives();

// Close DB Connection
mysql_close($dbConn);
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
		<h3>Share and Enjoy</h3>

<!-- Start WP Socializer - Social Buttons - Output -->
<div class="wp-socializer 32px">
<ul class="wp-socializer-opacity columns-no">
 <li><a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fwolfknives-random-name-generator%2F&amp;t=Wolfknives+Random+Name+Generator" title="Share this on Facebook" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Facebook" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -330px; border:0;"/></a></li> 

 <li><a href="http://twitter.com/home?status=Wolfknives+Random+Name+Generator%20-%20http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fwolfknives-random-name-generator%2F%20" title="Tweet this !" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Twitter" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1386px; border:0;"/></a></li> 

 <li><a href="http://delicious.com/post?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fwolfknives-random-name-generator%2F&amp;title=Wolfknives+Random+Name+Generator&amp;notes=Wolfknives+Random+Name+Generator." title="Post this on Delicious" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Delicious" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -132px; border:0;"/></a></li> 

 <li><a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fwolfknives-random-name-generator%2F&amp;title=Wolfknives+Random+Name+Generator" title="Submit this to StumbleUpon" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="StumbleUpon" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1287px; border:0;"/></a></li> 

 <li><a href="http://noyouare.lixlink.com/tjes/tjes-guests/" onclick="addBookmark(event);" title="Add to favorites" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Add to favorites" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -0px; border:0;"/></a></li> 

 <li><a href="mailto:?to=&amp;subject=Wolfknives+Random+Name+Generator&amp;body=Wolfknives+Random+Name+Generator.%20-%20http://noyouare.lixlink.com/tjes/wolfknives-random-name-generator/" title="Email this" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Email" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -297px; border:0;"/></a></li> 

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
		<script type="text/javascript">
		$(document).ready(function() {
			$("button, input:submit, a", ".btn").button();
			$("a", ".btn").click(function() {return true;});
		});
		</script>
<?php get_footer(); ?>

