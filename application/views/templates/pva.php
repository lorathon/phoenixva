<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<title><?php echo $meta_title; ?></title>		
		<meta name="description" content="<?php echo $meta_description; ?>">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
		
		<!-- Libs CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/owl-carousel/owl.carousel.css');?>" media="screen">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/owl-carousel/owl.theme.css');?>" media="screen">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/magnific-popup/magnific-popup.css');?>" media="screen">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/isotope/jquery.isotope.css');?>" media="screen">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/mediaelement/mediaelementplayer.css');?>" media="screen">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-elements.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-blog.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-shop.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-animate.css');?>">

		<!-- Current Page CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/rs-plugin/css/settings.css');?>" media="screen">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/circle-flip-slideshow/css/component.css');?>" media="screen">

		<!-- Responsive CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-responsive.css');?>" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/skins/default.css');?>">

		<!-- Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css');?>">

		<!-- Head Libs -->
		<script src="<?php echo base_url('assets/vendor/modernizr.js');?>"></script>

		<!--[if IE]>
			<link rel="stylesheet" href="<?php echo base_url('assets/css/ie.css');?>">
		<![endif]-->

		<!--[if lte IE 8]>
			<script src="<?php echo base_url('assets/vendor/respond.js');?>"></script>
		<![endif]-->

	</head>
	<body class="boxed">
		<div class="body">
			<header id="header">
				<div class="container">
					<h1 class="logo">
						<?php echo anchor('','<img alt="Phoenix Virtual Airways" 
								width="225" height="75" 
								data-sticky-width="180" data-sticky-height="60" 
								src="'.base_url('assets/img/Logo.png').'">','title="Home Page"'); ?>
					</h1>
					<nav>
						<ul class="nav nav-pills nav-top">
							<?php if (isset($userdata['name'])): ?>
								<li>
									<?php echo anchor('private/profile','<i class="fa fa-user fa-lg"></i>'.$userdata['name'],'title="Pilot Profile"'); ?>
								</li>
								<li>
									<?php echo anchor('auth/logout','<i class="fa fa-sign-out fa-lg"></i>','title="Sign Out"'); ?>
								</li>
							<?php else: ?>
								<li>
									<?php echo anchor('auth/login','<i class="fa fa-sign-in fa-lg"></i>Sign In'); ?>
								</li>
								<li>
									<?php echo anchor('auth/register','<i class="fa fa-angle-right fa-lg"></i>Join PVA'); ?>
								</li>
							<?php endif; ?>
						</ul>
					</nav>
					<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
						<i class="fa fa-bars"></i>
					</button>
				</div>
				<div class="navbar-collapse nav-main-collapse collapse">
					<div class="container">
						<ul class="social-icons">
							<li class="facebook"><a href="http://www.facebook.com/phoenixairways" target="_blank" title="Facebook">Facebook</a></li>
							<li class="twitter"><a href="http://www.twitter.com/" target="_blank" title="Twitter">Twitter</a></li>
						</ul>
						<nav class="nav-main mega-menu">
							<ul class="nav nav-pills nav-main" id="mainMenu">
								<li><?php echo anchor('','Home'); ?>
								<?php if (isset($userdata['name'])): ?>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown"
										   href="#">
											Public Pages <span class="caret"></span>
										</a>
										<ul class="dropdown-menu" role="menu">
								<?php endif; ?>
										<li><?php echo anchor('live','Live Ops'); ?>
										<li><?php echo anchor('pages/faq-public','FAQ\'s'); ?></li>
										<li class="dropdown">
											<a class="dropdown-toggle" data-toggle="dropdown"
										       href="#">
												About PVA <span class="caret"></span>
											</a>
											<ul class="dropdown-menu" role="menu">
												<li><?php echo anchor('pages/airports','Destinations'); ?></li>
												<li><?php echo anchor('pages/typeahead','Airlines'); ?></li>
												<li><?php echo anchor('aircraft','Aircraft Fleet'); ?></li>
												<li><?php echo anchor('hubs','Crew Centers'); ?></li>
												<li><?php echo anchor('pages/achievements','Achievements'); ?></li>
											</ul>
										</li>
								<?php if (isset($userdata['name'])): ?>
										</ul>
									</li>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">
											Pilot Tools <span class="caret"></span>
										</a>
										<ul class="dropdown-menu" role="menu">
											<li><?php echo anchor('private/profile','Your Profile'); ?>
											<li><?php echo anchor('private/schedules','Schedule Search'); ?></li>
											<li><?php echo anchor('private/bids','Bids'); ?></li>
											<li><?php echo anchor('private/brief','Flight Brief'); ?></li>
										</ul>
									</li>
									<li><?php echo anchor('http://phoenixva.org/forums/','Forums'); ?></li>
									<li><?php echo anchor('http://helpdesk.phoenixva.org/','Help'); ?></li>
									<?php if ($userdata['admin'] > 2): ?>
										<li><?php echo anchor('admin','Admin'); ?>
									<?php endif; // Admin ?>
								<?php endif; // Logged in ?>
							</ul>
						</nav>
					</div>
				</div>
			</header>
			<div role="main" class="main">
				<?php if (current_url() != base_url()): ?>
					<section class="page-top">
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<ul class="breadcrumb">
										<li><?php echo anchor('','Home'); ?></li>
									</ul>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h2><?php echo $title; ?></h2>
								</div>
							</div>
						</div>
					</section>
				<?php endif; ?>
				
				<?php if (isset($this->session) && $this->session->flashdata('title')): ?>
					<div class="container">
						<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<div class="panel panel-info">
									<div class="panel-heading">
										<?php echo $this->session->flashdata('title'); ?>
									</div>
									<div class="panel-body">
										<?php echo $this->session->flashdata('message'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif;?>
	
				<?php echo $view_output; ?>
			
			</div>
			
			<footer class="short" id="footer">
				<div class="container">
					<div class="row">
						<div class="col-md-8">
							<h4>About Phoenix Virtual Airways</h4>
							<p>
								Phoenix Virtual Airways brings forth experienced 
								virtual airline managers and a core group of pilots 
								who seek a rewarding, fun flying experience as part 
								of a community of flight simulation enthusiasts.
								<a href="#" class="btn-flat btn-xs">View More <i class="fa fa-arrow-right"></i></a>
							</p>
							<hr class="light">
						</div>
						<div class="col-md-3 col-md-offset-1">
							<h4>Contact Us</h4>
							<span class="phone">(800) 123-4567</span>
							<p class="short">International: (333) 456-6670</p>
							<p class="short">Fax: (222) 531-8999</p>
							<ul class="list icons list-unstyled">
								<li><i class="fa fa-envelope"></i> <?php echo safe_mailto('helpdesk@phoenixva.org','Email Us'); ?></li>
							</ul>
							<div class="social-icons">
								<ul class="social-icons">
									<li class="facebook"><a href="https://www.facebook.com/phoenixairways" target="_blank" data-placement="bottom" rel="tooltip" title="Facebook">Facebook</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-copyright">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<p align="center">© Copyright 2014. All Rights Reserved. For flight simulation purposes only. We are in no way affiliated with any real-world entities.</p>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>

		<!-- Libs -->
		<script src="<?php echo base_url('assets/vendor/jquery.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.appear.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.easing.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.cookie.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.validate.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.stellar.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.knob.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery.gmap.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/twitterjs/twitter.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/isotope/jquery.isotope.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/owl-carousel/owl.carousel.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jflickrfeed/jflickrfeed.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/magnific-popup/magnific-popup.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/mediaelement/mediaelement-and-player.js');?>"></script>
		
		<!-- Theme Initializer -->
		<script src="<?php echo base_url('assets/js/theme.plugins.js');?>"></script>
		<script src="<?php echo base_url('assets/js/theme.js');?>"></script>
		
		<!-- Current Page JS -->		
		<script src="<?php echo base_url('assets/vendor/rs-plugin/js/jquery.themepunch.plugins.min.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/rs-plugin/js/jquery.themepunch.revolution.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/circle-flip-slideshow/js/jquery.flipshow.js');?>"></script>
		<script src="<?php echo base_url('assets/js/views/view.home.js');?>"></script>
		
		<?php if (isset($scripts))
		{
			foreach ($scripts as $script)
			{
				echo $script."\n";
			}
		}
		?>		

		<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
		<script type="text/javascript">
		
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-12345678-1']);
			_gaq.push(['_trackPageview']);
		
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		
		</script>
		 -->

	</body>
</html>