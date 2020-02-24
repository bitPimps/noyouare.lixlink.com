<?php function DrawNavMain($navMainOn) { ?>
	<header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<div class="container">
				<a class="navbar-brand" href="/admin/index.php">NYA Admin</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarAdmin">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item<?php if($navMainOn=="Home"){?> active<?php } ?>">
							<a class="nav-link" href="/admin/index.php"><i class="fa fa-lg fa-home"></i></a>
						</li>
						<?php if($_SERVER['REMOTE_USER']=="bitpimps" || $_SERVER['REMOTE_USER']=="noyouare"){ ?>
						<li class="nav-item dropdown<?php if($navMainOn=="Guests" || $navMainOn=="PunchPad" || $navMainOn=="Quotes"){?> active<?php } ?>">
							<a class="nav-link dropdown-toggle" href="#" id="dropdownTJES" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TJES</a>
							<div class="dropdown-menu" aria-labelledby="dropdownTJES">
								<a class="dropdown-item<?php if($navMainOn=="Guests"){?> active<?php } ?>" href="/admin/guests/index.php">Guests</a>
								<a class="dropdown-item<?php if($navMainOn=="PunchPad"){?> active<?php } ?>" href="/admin/punchpad/index.php">PunchPad</a>
								<a class="dropdown-item<?php if($navMainOn=="Quotes"){?> active<?php } ?>" href="/admin/quotes/index.php">Quotes</a>
							</div>
						</li>
						<?php } ?>
						<li class="nav-item dropdown<?php if($navMainOn=="Wolfknives Member Registry" || $navMainOn=="Wolfknives Names" || $navMainOn=="Quotes"){?> active<?php } ?>">
							<a class="nav-link dropdown-toggle" href="#" id="dropdownTJES" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Wolfknives</a>
							<div class="dropdown-menu" aria-labelledby="dropdownTJES">
								<a class="dropdown-item<?php if($navMainOn=="Wolfknives Member Registry"){?> active<?php } ?>" href="/admin/wolfknives-registry/index.php">Wolfknives Member Registry</a>
								<a class="dropdown-item<?php if($navMainOn=="Wolfknives Names"){?> active<?php } ?>" href="/admin/wolfknives/index.php">Wolfknives Names Generator</a>
							</div>
						</li>
						<li class="nav-item<?php if($navMainOn=="EED"){?> active<?php } ?>">
							<a class="nav-link" href="/admin/eed/index.php">EED</a>
						</li>
					</ul>
					<ul class="navbar-nav my-2 my-md-0">
						<li class="nav-item">
							<a class="nav-link" href="/index.php">Sign out <i class="fa fa-sign-out"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>
<?php } ?>