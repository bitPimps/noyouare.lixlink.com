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
$dbConn = connectNYRDb();
// Range Variables
$MAX_NUM_ROWS = 1500;
$lowerBound = 0;
// Get DictSR
$dictSR = new DictSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $sName, $sortBy, "1");
$ellisWords = $dictSR->getDicts();
$totalNum = $dictSR->getTotalNum();
// Close DB Connection
mysqli_close($dbConn);
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
