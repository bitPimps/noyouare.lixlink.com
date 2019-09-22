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
 Template Name: TJES EllisFam
 */

get_header(); ?>
<link rel="stylesheet" href="http://noyouare.lixlink.com/wp-content/themes/noyouare/css/nya.css">
<?php
$showForm="Yes";
include_once("admin/sec/config/phpConfig.php");
include_once("admin/sec/config/common_db.php");

$msg="";
$msg_success="";
$mathAnswer="Adam";

(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") ? $whereFrom = $_SERVER['HTTP_REFERER'] : $whereFrom="No referer, direct request.";
(isset($_POST['wasSent']) && $_POST['wasSent'] != "") ? $wasSent=$_POST['wasSent'] : $wasSent="";
(isset($_POST['txtTwitterName']) && $_POST['txtTwitterName'] != "") ? $txtTwitterName=$_POST['txtTwitterName'] : $txtTwitterName="";
(isset($_POST['txtMathQuestion']) && $_POST['txtMathQuestion'] != "") ? $txtMathQuestion=$_POST['txtMathQuestion'] : $txtMathQuestion="";

// Connect to Database
$dbConn = connectNYRDb($dbHost, $dbUsername, $dbPassword);

if(isset($_POST['hereVar']) && $_POST['hereVar']=="true")
{
	// First, make sure the form was posted from a browser.
	// For basic web-forms, we don't care about anything
	// other than requests from a browser:
	if(!isset($_SERVER['HTTP_USER_AGENT'])){ 
  	 die("Forbidden - You are not authorized to view this page");
  	 exit;
	}
	// Make sure the form was indeed POST'ed:
	// (requires your html form to use: action="post")  
	if(!$_SERVER['REQUEST_METHOD'] == "POST"){ 
   	die("Forbidden - You are not authorized to view this page");
   	exit;
	}
	// Host names from where the form is authorized
	// to be posted from:  
	$authHosts = array("noyouare.lixlink.com");
	// Where have we been posted from?
	$fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER']));
	// Test to see if the $fromArray used www to get here.
	$wwwUsed = strpos($fromArray['host'], "www.");
	// Make sure the form was posted from an approved host name.
	if(!in_array(($wwwUsed === false ? $fromArray['host'] : substr(stristr($fromArray['host'], '.'), 1)), $authHosts)){ 
  	//logBadRequest();
  	header("Content-Type: text/html; charset=UTF-8");
		header("Location: " . basename($_SERVER['PHP_SELF']) . "");
    	exit;
	}
	// Attempt to defend against header injections:
	$badStrings = array("Content-Type:", 
                     	"MIME-Version:", 
                     	"Content-Transfer-Encoding:", 
                     	"bcc:", 
                     	"cc:",
											"to:",
											"<",
											">",
											".",
											"%",
											"$",
											"%",
											"^",
											"&",
											"*",
											"(",
											")",
											"+",
											"|",
											"[",
											"]",
											"{",
											"}",
											";",
											":",
											"href");
	// Loop through each POST'ed value and test if it contains
	// one of the $badStrings:
	foreach($_POST as $k => $v){ 
  	foreach($badStrings as $v2){ 
    	if(strpos($v, $v2) !== false){ 
           //logBadRequest();
           header("Content-Type: text/html; charset=UTF-8");
					 header("Location: " . basename($_SERVER['PHP_SELF']) . "");
               exit;
      } 
   	} 
	}
	// Made it past spammer test, free up some memory
	// and continue rest of script:
	unset($k, $v, $v2, $badStrings, $authHosts, $fromArray, $wwwUsed);

	//trim and validate emails comparing string to regular expression
	$txtTwitterName = trim(htmlspecialchars($txtTwitterName));
	$txtTwitterName = str_replace("@", "", $txtTwitterName);
	$txtTwitterName = str_replace(" ", "", $txtTwitterName);
	$txtMathQuestion = trim(htmlspecialchars($txtMathQuestion));

	if($txtTwitterName=="")
	{
		$msg="What's your fucking Twitter Name, dum-dum?";
	}
	elseif($txtMathQuestion=="" || $txtMathQuestion!=$mathAnswer)
	{
		$msg="You failed.";
	}
	else
	{
		require_once("admin/sec/phpinclude/classes/TwitterAPIExchange.php");
		/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
		$settings = array(
			'oauth_access_token' => "16506150-vS4cooGIfzDmj4bq6n5gP3WhjSW1Kc1eyteYiGqSe",
			'oauth_access_token_secret' => "d50UMITuPHf8LkPtg61A7gUhr45OKva1Clhb5ynDkQ",
			'consumer_key' => "ngsjHbDge5yBSJRlyeG7AQ",
			'consumer_secret' => "br6OUv5kGl4dRj2Yd0yV5Sf8e09zZDqMy5zN2cds"
		);
		/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
		$url = 'https://api.twitter.com/1.1/blocks/create.json';
		$requestMethod = 'GET';

		/** POST fields required by the URL above. See relevant docs as above **/
		$postfields = array(
			'screen_name' => $txtTwitterName,
			'skip_status' => '1'
		);

		/** Perform a POST request and echo the response **/
		$twitter = new TwitterAPIExchange($settings);
		$responseOne = $twitter->buildOauth($url, $requestMethod)
									->setPostfields($postfields)
									->performRequest();

		/** Perform a GET request and echo the response **/
		/** Note: Set the GET field BEFORE calling buildOauth(); **/
		$url = 'https://api.twitter.com/1.1/users/lookup.json';
		$getfield = "?screen_name=" . $txtTwitterName . "";
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$response = $twitter->setGetfield($getfield)
												->buildOauth($url, $requestMethod)
												->performRequest();
		//var_dump(json_decode($response));
		/*
		$twitter_url='http://api.twitter.com/1/users/show/'.$txtTwitterName.'.xml';
		$init = curl_init();
		curl_setopt ($init, CURLOPT_URL, $twitter_url);
		curl_setopt ($init, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($init, CURLOPT_NOBODY, 1);
		curl_setopt($init, CURLOPT_HEADER, 1);
		curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
		curl_exec($init);
		$result_header = curl_getinfo($init, CURLINFO_HTTP_CODE);
		curl_close($init);
		*/
		//if($result_header == "404")
		if (strpos($response,'Sorry, that page does not exist') !== false) {
			$msg="Bitch, according to Twitter, you don't even exist!";
		}
		else
		{
			$sql = "SELECT id FROM ellisfamtwiiters WHERE twitterName='" . $txtTwitterName . "'";
			$recordSetId = mysql_query($sql, $dbConn);
			if (!$recordSetId)
				handleDbError();

			$recordSet = mysql_fetch_row($recordSetId);
			$yourTwitterName = $recordSet[0];

			if($yourTwitterName!=0)
			{
				$msg="You already registered, ya pickle dick!";
				$wasSent="true";
			}
			else
			{
				//Try to save to DB
				$sql = "INSERT INTO ellisfamtwiiters (twitterName) VALUES ('" . $txtTwitterName . "')";
				$result = mysql_query($sql, $dbConn);
				if (!$result)
				{
					$msg="Whoops! An error occurred, it's not you, it's me...";
				}
				else
				{
					$msg_success="BOOM! You's on the EllisFam list, son.";
					$wasSent="true";
				}
			}
		}
	}
}
?>
		<div id="primary">
			<div id="content" role="main">

<article id="post-5580" class="post-5580 page type-page status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title">EllisFam</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<p>Here is a list of <a title="View #EllisFam related tweets" href="https://twitter.com/#!/search/%23EllisFam" target="_blank">#EllisFam</a> members. The list constantly grows and updates itself. Just fill out the form below and viola! You're on it! If you find someone below that isn't part of TJES and are just spamming a link to themselves, please let us know!</p>

		<?php if($showForm=="Yes"){ ?>
<div id="regForm">
<?php if (isset($msg) && $msg != ""){ ?><div class="ui-state-error ui-corner-all" style="margin-bottom:20px; padding:0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong><?php echo $msg; ?></strong></p></div><?php } ?>
<?php if (isset($msg_success) && $msg_success != ""){ ?><div class="ui-state-highlight ui-corner-all" style="margin-bottom:20px; padding:0 .7em;"><p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span><strong><?php echo $msg_success ?></strong></p></div><?php } ?>
<?php if($wasSent==""){ ?>
<form action="/tjes/ellisfam/" method="post" name="ellisfamReg">
<fieldset class="ui-corner-all">
<legend>Want to be on the #EllisFam list? Fill out this form</legend>
<input type="hidden" name="hereVar" value="true" />
<label for="txtTwitterName">What is your Twitter Name?<span class="required">*</span></label>
<div class="label_hint">Just your @TwitterName!</div>
<input type="text" name="txtTwitterName" id="txtTwitterName" value=""<?php if(isset($lightFullName) && $lightFullName != "") { ?> class="form_notice"<?php } ?> /><br />
<label for="txtMathQuestion" style="margin-top:20px;">What is Rawdog's real middle name?<span class="required">*</span></label>
<div class="label_hint">Prove you're not a spammer!</div>
<input type="text" name="txtMathQuestion" id="txtMathQuestion" value=""<?php if(isset($lightMathQuestion) && $lightMathQuestion != "") { ?> style="form_notice"<?php } ?> /><br />
<input type="submit" name="submit" value="Submit" class="button ui-corner-all" /><input type="reset" name="reset" value="Clear" class="button ui-corner-all" />
</fieldset>
</form>
<?php } ?>
</div>
<?php } ?>
<div id="listing">
<ul>
	<?php
	$sql = "SELECT * FROM ellisfamtwiiters ORDER BY id ASC";
	$recordSet = mysql_query($sql);
	while ($row = mysql_fetch_array($recordSet))
	{
		echo "<li><a href=\"https://twitter.com/" . $row['twitterName'] . "\" title=\"Follow @" . $row['twitterName'] . "\" target=\"_blank\">@" . $row['twitterName'] . "</a></li>";
	}
	?>
</ul>
</div>
<?php
// Close DB Connection
mysql_close($dbConn);
?>
<p style="clear:both;">&nbsp;</p>
		<h3>Share and Enjoy</h3>

<!-- Start WP Socializer - Social Buttons - Output -->
<div class="wp-socializer 32px">
<ul class="wp-socializer-opacity columns-no">
 <li><a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fellisfam%2F&amp;t=EllisFam" title="Share this on Facebook" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Facebook" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -330px; border:0;"/></a></li> 

 <li><a href="http://twitter.com/home?status=EllisFam%20-%20http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fellisfam%2F%20" title="Tweet this !" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Twitter" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1386px; border:0;"/></a></li> 

 <li><a href="http://delicious.com/post?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fellisfam%2F&amp;title=EllisFams&amp;notes=EllisFam." title="Post this on Delicious" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Delicious" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -132px; border:0;"/></a></li> 

 <li><a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fnoyouare.lixlink.com%2Ftjes%2Fellisfam%2F&amp;title=Ellisfam" title="Submit this to StumbleUpon" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="StumbleUpon" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -1287px; border:0;"/></a></li> 

 <li><a href="http://noyouare.lixlink.com/tjes/ellisfam/" onclick="addBookmark(event);" title="Add to favorites" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Add to favorites" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -0px; border:0;"/></a></li> 

 <li><a href="mailto:?to=&amp;subject=EllisFam&amp;body=EllisFam.%20-%20http://noyouare.lixlink.com/tjes/ellisfam/" title="Email this" target="_blank" rel="nofollow"><img src="http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-mask-32px.gif" alt="Email" style="width:32px; height:32px; background: transparent url(http://noyouare.lixlink.com/wp-content/plugins/wp-socializer/public/social-icons/wp-socializer-sprite-32px.png) no-repeat; background-position:0px -297px; border:0;"/></a></li> 

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
	$("button, input:submit, a", ".btn").button();
	$("a", ".btn").click(function() {return true;});
});
</script>