 <!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Admin | <?php echo $meta_title; ?></title>
                <meta name="description" content="<?php echo $meta_description; ?>">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css'); ?>">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
                
                <!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/bootstrap/css/bootstrap.css'); ?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/magnific-popup/magnific-popup.css'); ?>" />
		
		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css'); ?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/bootstrap-multiselect/bootstrap-multiselect.css'); ?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/vendor/morris/morris.css'); ?>" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/stylesheets/theme.css'); ?>" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/stylesheets/skins/default.css'); ?>" />
                
                <!-- load page specific CSS -->
     			<?php foreach ($stylesheets as $row): ?>
                            <link rel="stylesheet" href="<?=$row; ?>">
                        <?php endforeach; ?>

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/stylesheets/theme-custom.css'); ?>">

		<!-- Head Libs -->
		<script src="<?php echo base_url('assets/admin/vendor/modernizr/modernizr.js'); ?>"></script>

	</head>
	<body>
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="<?php echo base_url(); ?>" class="logo">
						<img src="<?php echo base_url('assets/img/Logo.png'); ?>" height="35" alt="PVA Admin" />
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
			
					<form action="pages-search-results.html" class="search nav-form">
						<div class="input-group input-search">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name"><?php echo $userdata['name']; ?></span>
								<span class="role">administrator</span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="private/profile"><i class="fa fa-user"></i> My Profile</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="auth/logout"><i class="fa fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							Navigation
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li class="nav-active">
										<a href="<?php echo base_url('admin'); ?>">
											<i class="fa fa-home" aria-hidden="true"></i>
											<span>Dashboard</span>
										</a>
									</li>
									<li>
										<a href="#S">
											<span class="pull-right label label-primary">2</span>
											<i class="fa fa-envelope" aria-hidden="true"></i>
											<span>Pending Applications</span>
										</a>
									</li>
                                                                        <li>
										<a href="#S">
											<span class="pull-right label label-primary">2</span>
											<i class="fa fa-plane" aria-hidden="true"></i>
											<span>Pending Flight Reports</span>
										</a>
									</li>
									<li class="nav-parent">
										<a>
											<i class="fa fa-group" aria-hidden="true"></i>
											<span>Pilots</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<?php echo anchor('admin/users/active','Active Pilots'); ?>
											</li>
											<li>
												<?php echo anchor('admin/users/inactive','Inactive Pilots'); ?>
											</li>
                                                                                        <li>
												<?php echo anchor('admin/ranks','Pilot Ranks'); ?>
											</li>
										</ul>
									</li>
									
									<!-- Start Awards Nav -->
									<li class="nav-parent">
										<a>
											<i class="fa fa-trophy" aria-hidden="true"></i>
											<span>Awards</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<?php echo anchor('admin/award_admin', 'All Awards'); ?>
											</li>
											<li class="nav-parent">
												<a>Tools</a>
												<ul class="nav nav-children">													
													<li>
														<?php echo anchor('admin/award_admin/award_types', 'Award Types'); ?>
													</li>													
												</ul>
											</li>
										</ul>
									</li> 
									<!-- End Awards Nav -->
                                                                        
                                                                        <li class="nav-parent">
										<a>
											<i class="fa fa-copy" aria-hidden="true"></i>
											<span>Flights (PIREPs)</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<?php echo anchor('admin/pireps/recent','View Recent PIREPs'); ?>
											</li>
											<li>
												<?php echo anchor('admin/pireps/all','View All PIREPs'); ?>
											</li>
                                                                                        <li>
												<?php echo anchor('admin/pireps/bids','Current Bids'); ?>
											</li>
										</ul>
									</li>
                                                                        
                                                                        <li class="nav-parent">
										<a>
											<i class="fa fa-university" aria-hidden="true"></i>
											<span>Airline Ops</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<?php echo anchor('admin/airports','View Airports'); ?>
											</li>
                                                                                        <li>
												<?php echo anchor('admin/airlines','View Airlines'); ?>
											</li>                                                                                        
											<li class="nav-parent">
												<a>Fleet </a>
												<ul class="nav nav-children">
													<li>
														<?php echo anchor('admin/fleet','View Fleet'); ?>
													</li>
													<li>
														<?php echo anchor('admin/fleet/substitutions', 'Fleet Substituions'); ?>
													</li>
													<li>
														<?php echo anchor('admin/fleet/missing_sub', 'Missing Substituions'); ?>
													</li>
													<li>
														<?php echo anchor('admin/fleet/build_fleet', 'Build Fleet'); ?>
													</li>
												</ul>
											</li>
                                                                                        <li>
												<?php echo anchor('admin/schedules','View Schedules'); ?>
											</li>
										</ul>
									</li>
                                                                        
									<!-- Start Event Nav -->
                                                                        <li class="nav-parent">
										<a>
											<i class="fa fa-cubes" aria-hidden="true"></i>
											<span>Events Dept</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<?php echo anchor('admin/event_admin', 'All Events'); ?>
											</li>
											<li class="nav-parent">
												<a>Tools</a>
												<ul class="nav nav-children">													
													<li>
														<?php echo anchor('admin/event_admin/event_types', 'Event Types'); ?>
													</li>													
												</ul>
											</li>
										</ul>
									</li>
									<!-- End Event Nav -->
                                                                        
                                                                        <li class="nav-parent">
										<a>
											<i class="fa fa-cogs" aria-hidden="true"></i>
											<span>Site Settings</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<?php echo anchor('admin/managedata','Flightstats Data'); ?>
											</li>
                                                                                        <li>
                                                                                                <?php echo anchor('admin/settings/general','General Settings'); ?>
											</li>
                                                                                        <li>
												<?php echo anchor('admin/settings/pvacars','PVACARS Settings'); ?>
											</li>
                                                                                        <li>
												<?php echo anchor('admin/logs','Admin Activity Logs'); ?>
											</li>
                                                                                        
                                                                                        
										</ul>
									</li>
      								</ul>
							</nav>
						</div>
				
					</div>
				
				</aside>
				<!-- end: sidebar -->
                                
                                <section role="main" class="content-body">                                    
				    
				    <!--
                                    <?php if (isset($this->session) && $this->session->flashdata('title')): ?>
					<div class="container">
						<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<div class="panel panel-<?php echo $this->session->flashdata('msg_type'); ?>">
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
                                    <?php endif;?>-->
				    
				    <?php if (isset($this->session) && $this->session->flashdata('title')): ?>
					<div class="alert alert-<?php echo $this->session->flashdata('msg_type'); ?>">            
					    <p><?php echo $this->session->flashdata('message'); ?></p>
					</div>
                                    <?php endif;?>
				    
				    <?php if ($errors): ?>
					<div class="alert alert-danger">            
					    <p><?php echo $errors; ?></p>
					</div>
				    <?php endif; ?>
				    
				    <?php if ($alert): ?>
					<div class="alert alert-<?php echo $alert['type'] ?>">            
					    <p><?php echo $alert['msg']; ?></p>
					</div>
				    <?php endif; ?>

				<?php echo $view_output; ?>

					
					
					<!-- end: page -->
				</section>
			</div>
		</section>

		<!-- Vendor -->
                <script src="<?php echo base_url('assets/admin/vendor/jquery/jquery.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/jquery-browser-mobile/jquery.browser.mobile.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/bootstrap/js/bootstrap.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/nanoscroller/nanoscroller.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/magnific-popup/magnific-popup.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/jquery-placeholder/jquery.placeholder.js'); ?>"></script>
		
		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url('assets/admin/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/jquery-appear/jquery.appear.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/bootstrap-multiselect/bootstrap-multiselect.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/jquery-easypiechart/jquery.easypiechart.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/flot/jquery.flot.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/flot-tooltip/jquery.flot.tooltip.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/flot/jquery.flot.pie.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/flot/jquery.flot.categories.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/flot/jquery.flot.resize.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/jquery-sparkline/jquery.sparkline.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/raphael/raphael.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/morris/morris.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/gauge/gauge.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/snap-svg/snap.svg.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/vendor/liquid-meter/liquid.meter.js'); ?>"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url('assets/admin/javascripts/theme.js'); ?>"></script>
                
                <!-- load page specific scripts -->
        <?php foreach ($scripts as $row): ?>
        	<script src="<?=$row; ?>"></script>
        <?php endforeach; ?>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url('assets/admin/javascripts/theme.custom.js'); ?>"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url('assets/admin/javascripts/theme.init.js'); ?>"></script>


		<!-- Examples -->
		<script src="<?php echo base_url('assets/admin/javascripts/dashboard/examples.dashboard.js'); ?>"></script>
	</body>
</html>
