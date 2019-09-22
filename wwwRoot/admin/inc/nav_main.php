<?php function DrawNavMain($navMainOn) { ?>
	<!--[if lt IE 7]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/admin/index.php">NYA Administration</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li<?php if($navMainOn=="Home"){?> class="active"<?php } ?>><a href="/admin/index.php">Admin Home</a></li>
					<?php if($_SERVER['REMOTE_USER']=="bitpimps" || $_SERVER['REMOTE_USER']=="noyouare"){ ?>
					<li class="dropdown<?php if($navMainOn=="Guests" || $navMainOn=="PunchPad" || $navMainOn=="Quotes"){?> active<?php } ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">TJES <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li<?php if($navMainOn=="Guests"){?> class="active"<?php } ?>><a href="/admin/guests/index.php">Guests</a></li>
							<li<?php if($navMainOn=="PunchPad"){?> class="active"<?php } ?>><a href="/admin/punchpad/index.php">Punch-Pad Results</a></li>
							<li<?php if($navMainOn=="Quotes"){?> class="active"<?php } ?>><a href="/admin/quotes/index.php">Quotes</a></li>
						</ul>
					</li>
					<?php } ?>
					<li class="dropdown<?php if($navMainOn=="Wolfknives Member Registry" || $navMainOn=="Wolfknives Names"){?> active<?php } ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Wolfknives <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li<?php if($navMainOn=="Wolfknives Member Registry"){?> class="active"<?php } ?>><a href="/admin/wolfknives-registry/index.php">Membership Registry</a></li>
							<li<?php if($navMainOn=="Wolfknives Names"){?> class="active"<?php } ?>><a href="/admin/wolfknives/index.php">Name Generator</a></li>
						</ul>
					</li>
					<li<?php if($navMainOn=="EED"){?> class="active"<?php } ?>><a href="/admin/eed/index.php">EED</a></li>
				</ul>
			</div><!--/.navbar-collapse -->
		</div>
	</div>
<?php } ?>