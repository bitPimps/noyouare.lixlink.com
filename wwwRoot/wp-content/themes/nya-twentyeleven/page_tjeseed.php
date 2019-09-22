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
 * @subpackage Twenty_Eleven
 * @since No You Are 1.0
 *
 Template Name: TJES EED
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
include("admin/sec/config/phpConfig.php");
include("admin/sec/config/common_db.php");
include("admin/sec/phpinclude/classes/Dict.php");
include("admin/sec/phpinclude/classes/DictSR.php");
// Connect to Database
$dbConn = connectNYRDb($dbHost, $dbUsername, $dbPassword);
// Range Variables
$MAX_NUM_ROWS = 1500;
$lowerBound = 0;
// Get DictSR
$dictSR = new DictSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, $sortBy, "1");
$ellisWords = $dictSR->getDicts();
$totalNum = $dictSR->getTotalNum();
// Close DB Connection
mysql_close($dbConn);
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">Ellis English Dictionary</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<p><img src="http://noyouare.lixlink.com/wp-content/uploads/2014/09/eed.jpg" width="250" align="right" style="margin:0 0 20px 20px;" />Welcome to the Ellis English Dictionary!</p>
		<p>For Ellis English Dictionary updates, follow the official Twitter account <a href="https://twitter.com/OfficialEED" target="_blank">@OfficialEED</a>.</p>
		<p>This list is managed and maintained by <a href="https://twitter.com/RainManRDS" target="_blank">@RainManRDS</a> and <a href="https://twitter.com/waydub" target="_blank">@waydub</a>.</p>
		<p>If you would like to contribute, please contact <a href="https://twitter.com/OfficialEED" target="_blank">@OfficialEED</a>.</p>
		<p><strong>Current number of entries: <?php echo $totalNum ?></strong></p>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblDictionary">
			<thead>
			<tr>
				<th><div style="font-size:14px; font-weight:bold;">What Ellis Says</div></th>
				<th><div style="font-size:14px; font-weight:bold;">What Ellis Means</div></th>
			</tr>
			</thead>
			<tbody>
			<?php for ($ti = 0; $ti<count($ellisWords); $ti++){ ?>
			<tr>
				<td><?php echo $ellisWords[$ti]->getTerm(); ?></td>
				<td><?php echo $ellisWords[$ti]->getDefinition(); ?></td>
			</tr>
			<?php } if(count($ellisWords)==0){ ?>
			<tr>
				<td colspan="2"><small>No Terms have been recorded...</small></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		<h3>Share and Enjoy</h3>

<!-- Start WP Socializer - Social Buttons - Output -->
<div class="wp-socializer 32px">
<ul class="wp-socializer-opacity columns-no">
 <li><a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Feed%2F&amp;t=Ellis+English+Dictionary" title="Share this on Facebook" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Facebook" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -330px; border:0;"/></a></li> 

 <li><a href="http://twitter.com/home?status=Ellis+English+Dictionary%20-%20http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Feed%2F%20" title="Tweet this !" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Twitter" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1386px; border:0;"/></a></li> 

 <li><a href="http://delicious.com/post?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Feed%2F&amp;title=Ellis+English+Dictionary&amp;notes=Ellis+English+Dictionary." title="Post this on Delicious" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Delicious" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -132px; border:0;"/></a></li> 

 <li><a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Feed%2F&amp;title=Ellis+English+Dictionary" title="Submit this to StumbleUpon" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="StumbleUpon" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1287px; border:0;"/></a></li> 

 <li><a href="http://noyouare.lixlink.com/tjes/eed/" onclick="addBookmark(event);" title="Add to favorites" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Add to favorites" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -0px; border:0;"/></a></li> 

 <li><a href="mailto:?to=&amp;subject=Wolfknives+Membership+Registry&amp;body=Ellis+English+Dictionary.%20-%20http://noyouare.lixlink.com/tjes/eed/" title="Email this" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Email" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -297px; border:0;"/></a></li> 

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
	$(".stripeMe tr").mouseover(function(){$(this).addClass("over");}).mouseout(function(){$(this).removeClass("over");});
	$(".stripeMe tr:nth-child(even)").addClass("alt");
});
</script>
