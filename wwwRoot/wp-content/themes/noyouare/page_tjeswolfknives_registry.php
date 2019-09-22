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
 Template Name: TJES WK Registry
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
include("admin/sec/config/phpConfig.php");
include("admin/sec/config/common_db.php");
include("admin/sec/phpinclude/classes/WolfknivesMember.php");
include("admin/sec/phpinclude/classes/WolfknivesMemberSR.php");
// Connect to Database
$dbConn = connectNYRDb();
// Range Variables
$MAX_NUM_ROWS = 1500;
$lowerBound = 0;
(isset($_POST['sortBy']) && $_POST['sortBy'] != "") ? $sortBy=htmlentities($_POST['sortBy'], ENT_QUOTES, "UTF-8") : $sortBy="";
// Get WolfknivesMemberSR
$wolfknivesMemberSR = new WolfknivesMemberSR($dbConn, $lowerBound, $MAX_NUM_ROWS, $searchCrit, $sortBy, "1");
$wolfknivesMembers = $wolfknivesMemberSR->getWolfknivesMembers();
$totalNum = $wolfknivesMemberSR->getTotalNum();
// Close DB Connection
mysqli_close($dbConn);
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">Wolfknives Membership Registry</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<p><img src="http://noyouare.lixlink.com/wp-content/uploads/2012/08/logo_wolfknives.jpg" width="250" align="right" style="margin:0 0 20px 20px;" />Welcome to the Unofficial Membership Registry of The Wolfknives.</p>
		<p>This is a list of Wolfknives Members that have registered or have been otherwise added to the Wolfknives Membership Registry, which is currated by <a href="https://twitter.com/RainManRDS" target="_blank">@RainManRDS</a>.</p>
		<p>If you are a Wolfknives member and want to register, contact <a href="https://twitter.com/RainManRDS" target="_blank">@RainManRDS</a>. He administer's the list, we just publish it.</p>
		<p>Looking to just have fun? Maybe you'll like our <a href="http://noyouare.lixlink.com/tjes/wolfknives-random-name-generator/">Wolfknives Random Name Generator</a>! We don't give out Wolfknives names, only Ellis &amp; crew does that. But hey, it's still good for a few laughs!</p>
		<p><strong>Current Wolfknives Members: <?php echo $totalNum ?></strong></p>
		<form action="http://noyouare.lixlink.com/tjes/wolfknives-membership-registry/" method="post" name="wkMemberSearch">
			<fieldset class="ui-corner-all">
			<legend>Sort By:</legend>
			<select name="sortBy" size="1" id="sortBy" onChange="this.form.submit()">
				<option value="nameWK"<?php if($sortBy=="" || $sortBy=="nameWK"){ ?> selected<?php } ?>>Wolfknives Name</option>
				<option value="firstNameReal"<?php if($sortBy=="firstNameReal"){ ?> selected<?php } ?>>First Name</option>
				<option value="nameTwitter"<?php if($sortBy=="nameTwitter"){ ?> selected<?php } ?>>Twitter Name</option>
				<option value="nameInstagram"<?php if($sortBy=="nameInstagram"){ ?> selected<?php } ?>>Instagram Name</option>
				<option value="nameOJE"<?php if($sortBy=="nameOJE"){ ?> selected<?php } ?>>Official Jason Ellis.com Name</option>
				<option value="state"<?php if($sortBy=="state"){ ?> selected<?php } ?>>State</option>
				<option value="rank"<?php if($sortBy=="rank"){ ?> selected<?php } ?>>Rank</option>
			</select>
			</fieldset>
		</form>
		<p>&nbsp;</p>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" class="stripeMe" id="tblPunches">
			<thead>
			<tr>
				<th><div style="font-size:14px; font-weight:bold;">Wolfknives</div></th>
				<th><div style="font-size:14px; font-weight:bold;">Real</div></th>
				<th><div style="font-size:14px; font-weight:bold;">State</div></th>
				<th>
					<div style="text-align:center; font-size:14px; font-weight:bold;">
						<img src="http://noyouare.lixlink.com/wp-content/themes/noyouare/images/icons/twitter.png" border="0"> 
						<img src="http://noyouare.lixlink.com/wp-content/themes/noyouare/images/icons/instagram.png" border="0"> 
						<img src="http://noyouare.lixlink.com/wp-content/themes/noyouare/images/icons/officialjasonellis.png" border="0">
					</div>
				</th>
				<th><div style="text-align:center; font-size:14px; font-weight:bold;">Rank</div></th>
			</tr>
			</thead>
			<tbody>
			<?php for ($wkmr = 0; $wkmr<count($wolfknivesMembers); $wkmr++){ ?>
			<tr>
				<td>
					<?php echo $wolfknivesMembers[$wkmr]->getNameWK(); ?>
					<?php if($wolfknivesMembers[$wkmr]->getAccolades()!=""){ ?><br /><small style="color:#fc0;">(<?php echo $wolfknivesMembers[$wkmr]->getAccolades(); ?>)</small><?php } ?>
				</td>
				<td><?php echo $wolfknivesMembers[$wkmr]->getFirstNameReal(); ?><?php if($wolfknivesMembers[$wkmr]->getLastNameReal()!=""){ ?> <?php echo $wolfknivesMembers[$wkmr]->getLastNameReal(); ?><?php } ?></td>
				<td><?php if($wolfknivesMembers[$wkmr]->getState()!=""){ ?><?php echo $wolfknivesMembers[$wkmr]->getState(); ?><?php } else { ?>&nbsp;<?php } ?></td>
				<td>
					<?php if($wolfknivesMembers[$wkmr]->getNameTwitter()!=""){ ?><div style="margin-bottom:2px; clear:both;"><img src="http://noyouare.lixlink.com/wp-content/themes/noyouare/images/icons/twitter.png" border="0"> <a href="https://twitter.com/<?php echo $wolfknivesMembers[$wkmr]->getNameTwitter(); ?>" target="_blank">@<?php echo $wolfknivesMembers[$wkmr]->getNameTwitter(); ?></a></div><?php } ?>
					<?php if($wolfknivesMembers[$wkmr]->getNameInstagram()!=""){ ?><div style="margin-bottom:2px; clear:both;"><img src="http://noyouare.lixlink.com/wp-content/themes/noyouare/images/icons/instagram.png" border="0"> <a href="http://instagram.com/<?php echo $wolfknivesMembers[$wkmr]->getNameInstagram(); ?>" target="_blank">@<?php echo $wolfknivesMembers[$wkmr]->getNameInstagram(); ?></a></div><?php } ?>
					<?php if($wolfknivesMembers[$wkmr]->getNameOJE()!=""){ ?><div style="margin-bottom:2px; clear:both;"><img src="http://noyouare.lixlink.com/wp-content/themes/noyouare/images/icons/officialjasonellis.png" border="0"> <?php echo $wolfknivesMembers[$wkmr]->getNameOJE(); ?></div><?php } ?>
				</td>
				<td><?php if($wolfknivesMembers[$wkmr]->getRank()!=""){ ?><?php echo $wolfknivesMembers[$wkmr]->getRank(); ?><?php } else { ?>&nbsp;<?php } ?></td>
			</tr>
			<?php } if(count($wolfknivesMembers)==0){ ?>
			<tr>
				<td colspan="5"><small>No Wolfknives Members have been recorded...</small></td>
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
