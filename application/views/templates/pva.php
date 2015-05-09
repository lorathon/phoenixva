<!DOCTYPE html>
<html>
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<title><?php echo $meta_title; ?></title>		
		<meta name="description" content="<?php echo $meta_description; ?>">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
                <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
                
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
                
                <!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-elements.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/theme-animate.css');?>">
                <link rel="stylesheet" href="<?php echo base_url('assets/admin/stylesheets/theme-admin-extension.css');?>">
                
                <!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/skins/default.css');?>">
                <link rel="stylesheet" href="<?php echo base_url('assets/admin/stylesheets/skins/extension.css');?>">
                
                <!-- load page specific CSS -->
     			<?php foreach ($stylesheets as $row): ?>
                            <link rel="stylesheet" href="<?=$row; ?>">
                        <?php endforeach; ?>

		<!-- Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css');?>">

		<!-- Head Libs -->
		<script src="<?php echo base_url('assets/vendor/modernizr/modernizr.js');?>"></script>
                

		<!--[if IE]>
			<link rel="stylesheet" href="css/ie.css">
		<![endif]-->

		<!--[if lte IE 8]>
			<script src="vendor/respond/respond.js"></script>
			<script src="vendor/excanvas/excanvas.js"></script>
		<![endif]-->

	</head>
	<body class="sticky-menu-active">
           
		<div class="body">
                    <header id="header" class="narrow" data-plugin-options='{"alwaysStickyEnabled": true, "stickyEnabled": true, "stickyWithGap": false, "stickyChangeLogoSize": false}'>
				<div class="container">
					<h1 class="logo">
						<?php echo anchor('','<img alt="Phoenix Virtual Airways" width="180" height="60" src="'.base_url('assets/img/Logo.png').'">','title="Home Page"'); ?>
					</h1>
					<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
						<i class="fa fa-bars"></i>
					</button>
				</div>
				<div class="navbar-collapse nav-main-collapse collapse">
					<div class="container">
                                                <?php if (isset($userdata['name'])): ?>
                                                    <ul class="help-links">
                                                        <a href="http://www.phoenixva.org/forums/index.php?app=members&module=messaging"><i class="fa fa-inbox fa-lg"></i></a>
                                                        <a href="http://helpdesk.phoenixva.org/"><i class="fa fa-life-ring fa-lg"></i></a>
                                                    </ul>
                                                <?php else: ?> 
                                                    <ul class="social-icons">
							<li class="facebook"><?php echo anchor('http://www.facebook.com/phoenixairways','title="Facebook"'); ?></li>
                                                        <li class="twitter"><?php echo anchor('http://www.twitter.com/phoenixairways','title="Twitter"'); ?></li>
                                                    </ul>
                                                <?php endif; ?> 
						<nav class="nav-main mega-menu">
							<ul class="nav nav-pills nav-main" id="mainMenu">
								<li><?php echo anchor('live','Live Ops'); ?></li>
								
                                                                <!-- if not logged in, show about us and public FAQ page on bar -->
                                                                <?php if ( ! isset($userdata['name'])): ?>
                                                                    <li><?php echo anchor('pages/about','About Us'); ?></li>
                                                                    <li><?php echo anchor('pages/faq-public','FAQ\'s'); ?></li>
                                                                <?php endif; ?>
                                                                        
                                                                <li class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown">
                                                                                General Info<span class="caret"></span>
                                                                        </a>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                        <?php if (isset($userdata['name'])): ?>                    
                                                                            <li><?php echo anchor('pages/about','About Us'); ?></li>
                                                                            <li><?php echo anchor('pages/faq-crew','Crew FAQ\'s'); ?></li>
                                                                        <?php endif; ?>			

                                                                            <li><?php echo anchor('airports','Destinations'); ?></li>
									    <li><?php echo anchor('airlines','Airlines'); ?></li>
                                                                            <li><?php echo anchor('fleet','Aircraft Fleet'); ?></li>
                                                                            <li><?php echo anchor('hubs','Crew Centers'); ?></li>
                                                                            <li><?php echo anchor('events','Events'); ?></li>
                                                                            <li><?php echo anchor('awards','Achievements'); ?></li>
									    <li><?php echo anchor('ranks','Ranks'); ?></li>
                                                                        </ul>
                                                                </li>

                                                                <li><?php echo anchor('http://phoenixva.org/forums/','Forums'); ?></li>

                                                                <?php if (isset($userdata['name']) && $userdata['is_admin']): ?>
                                                                        <li><?php echo anchor('admin','Admin'); ?>
                                                                <?php endif; // Admin ?>
								
                                                               
                                                                <!-- PILOT PROFILE DROPDOWN -->
                                                                
                                                                <?php if (isset($userdata['name'])): //Logged In?>
                                                                <li class="dropdown mega-menu-item mega-menu-signin signin logged" id="headerAccount">
									<?php echo anchor('private/profile',
											'<i class="fa fa-user fa-lg"></i> '
											.$userdata['rank_short'] . ' ' . $userdata['name']
											.'<i class="fa fa-angle-down"></i>','class="dropdown-toggle"'); ?>
									<ul class="dropdown-menu">
										<li>
											<div class="mega-menu-content">
												<div class="row">
													<div class="col-md-6">
														<div class="user-avatar">
															<div class="img-thumbnail">
																<img src="img/clients/client-1.jpg" alt="">
															</div>
															<p><strong><?php echo $userdata['name']; ?></strong><span><?php echo $userdata['rank_name']; ?></span></p>
														</div>
													</div>
													<div class="col-md-6">
														<ul class="list-account-options">
															<li>
																<?php echo anchor('private/profile','Your Profile'); ?>
															</li>
                                                                                                                        <li>
																<?php echo anchor('private/bids','Current Bids'); ?>
															</li>
                                                                                                                        <li>
																<?php echo anchor('private/schedules','Schedule Search'); ?>
															</li>
                                                                                                                        <li>
                                                                                                                                <?php echo anchor('private/brief','Flight Brief'); ?>
                                                                                                                        </li>
															<li>
																<?php echo anchor('auth/logout','Sign Out'); ?>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</li>
                                                                
                                                                <?php else: // Sign in page?>
                                                                
								<li class="dropdown mega-menu-item mega-menu-signin signin" id="headerAccount">
									<a class="dropdown-toggle" href="auth/login">
										<i class="fa fa-user fa-lg"></i> Sign In
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu">
										<li>
											<div class="mega-menu-content">
												<div class="row">
													<div class="col-md-12">

														<div class="signin-form">

															<span class="mega-menu-sub-title">Sign In</span>
															<?php $this->load->helper('form'); ?>
															<?php echo form_open('auth/login'); ?>
																<div class="row">
																	<div class="form-group">
																		<div class="col-md-12">
																			<label>Username or E-mail Address</label>
																			<input type="text"
																			       name="login"
																			       id="login"
																			       value="<?php echo set_value('login'); ?>" 
																			       class="form-control input-lg">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<div class="col-md-12">
																			<?php echo anchor('/auth/forgot_password/', '(Lost Password?)', 'class="pull-right" id="headerRecover"'); ?>
																			<label>Password</label>
																			<input type="password" 
																			       name="password"
																			       id="password"
																			       value="" 
																			       class="form-control input-lg">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-6">
																		<span class="remember-box checkbox">
																			<label for="rememberme">
																				<input type="checkbox" 
																				       id="remember" 
																				       name="remember"
																				       value="1"
																				       checked="<?php echo set_value('remember'); ?>">Remember Me
																			</label>
																		</span>
																	</div>
																	<div class="col-md-6">
																		<input type="submit" value="Login" class="btn btn-primary pull-right push-bottom" data-loading-text="Loading...">
																	</div>
																</div>
															</form>

															<p class="sign-up-info">Don't have an account yet? <?php echo anchor('auth/register','Sign Up!'); ?></p>

														</div>

														<div class="signup-form">
															<span class="mega-menu-sub-title">Create Account</span>

															<form action="" id="" method="post">
																<div class="row">
																	<div class="form-group">
																		<div class="col-md-12">
																			<label>E-mail Address</label>
																			<input type="text" value="" class="form-control input-lg">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<div class="col-md-6">
																			<label>Password</label>
																			<input type="password" value="" class="form-control input-lg">
																		</div>
																		<div class="col-md-6">
																			<label>Re-enter Password</label>
																			<input type="password" value="" class="form-control input-lg">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-12">
																		<input type="submit" value="Create Account" class="btn btn-primary pull-right push-bottom" data-loading-text="Loading...">
																	</div>
																</div>
															</form>

															<p class="log-in-info">Already have an account? <a href="#" id="headerSignIn">Log In!</a></p>
														</div>

														<div class="recover-form">
															<span class="mega-menu-sub-title">Reset My Password</span>
															<p>Complete the form below to receive an email with the authorization code needed to reset your password.</p>

															<form action="" id="" method="post">
																<div class="row">
																	<div class="form-group">
																		<div class="col-md-12">
																			<label>E-mail Address</label>
																			<input type="text" value="" class="form-control input-lg">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-12">
																		<input type="submit" value="Submit" class="btn btn-primary pull-right push-bottom" data-loading-text="Loading...">
																	</div>
																</div>
															</form>

															<p class="log-in-info">Already have an account? <a href="#" id="headerRecoverCancel">Log In!</a></p>
														</div>

													</div>
												</div>
											</div>
										</li>
                                                                                
									</ul>
								</li>
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
									<?php if (isset($breadcrumb)): ?>
										<?php foreach ($breadcrumb as $link => $page): ?>
											<li><?php echo anchor($link, $page); ?></li>
										<?php endforeach; ?>
									<?php endif; ?>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h1><?php echo $title; ?></h1>
							</div>
						</div>
					</div>
				</section>
                            <?php endif; ?>
                            			    
                 
				<?php if (isset($this->session) && $this->session->flashdata('title')): ?>
					<!-- _flash_messsage is deprecated. Use _alert() instead
					<div class="container">
						<div class="row">
							<div class="col-md-12">
							    <div class="alert alert-<?php echo $this->session->flashdata('msg_type'); ?>">            
								<p><?php echo $this->session->flashdata('message'); ?></p>
							    </div>
							</div>
						</div>
					</div>
					-->			    
                <?php endif;?>
                 
                
                <?php
                	if (isset($this->session) && $this->session->flashdata('alerts'))
                	{
                		if (isset($alerts))
                		{
                			$alerts = array_merge($this->session->flashdata('alerts'), $alerts);
                		}
                		else 
                		{
                			$alerts = $this->session->flashdata('alerts');
                		}
                	} 
                	if (isset($alerts)): ?>
				   	<?php foreach ($alerts as $alert): ?>
				   		<div class="alert alert-<?php echo $alert['type']; ?>">            
				    		<p><?php echo $alert['msg']; ?></p>
						</div>
				   	<?php endforeach; ?>
				<?php endif; ?>
                
			    
				<?php if ($errors):?>
					<div class="container">
						<div class="row">
							<div class="col-md-offset-3 col-md-6">
								<div class="panel panel-danger">
									<div class="panel-heading">
										Errors (DEPRECATED FEATURE, PLEASE NOTIFY HELPDESK)
									</div>
									<div class="panel-body">
										<?php foreach ($errors as $error): ?>
											<p><?php echo $error; ?></p>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
	
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
							
							<ul class="list icons list-unstyled">
								<li><i class="fa fa-envelope"></i> <?php echo safe_mailto('helpdesk@phoenixva.org','Email Us'); ?></li>
							</ul>
							<div class="social-icons">
								<ul class="social-icons">
									<li class="facebook"><a href="https://www.facebook.com/phoenixairways" target="_blank" data-placement="bottom" rel="tooltip" title="Facebook">Facebook</a></li>
                                                                        <li class="twitter"><a href="https://www.twitter.com/phoenixairways" target="_blank" data-placement="bottom" rel="tooltip" title="Twitter">Twitter</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-copyright">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<p align="center">© Copyright 2015. Phoenix Virtual Airways Inc. All Rights Reserved. For flight simulation purposes only. We are in no way affiliated with any real-world entities.</p>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
                
                <!-- Jquery -->
                <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
                <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
                
                <!-- bootstrap -->
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
                                         
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url('assets/js/theme.js');?>"></script>
                
        <!-- load page specific scripts -->
        <?php foreach ($scripts as $row): ?>
        	<script src="<?=$row; ?>"></script>
        <?php endforeach; ?>

		<!-- Admin Extension -->
		<script src="<?php echo base_url('assets/admin/javascripts/theme.admin.extension.js');?>"></script>
		
		<!-- Custom -->
		<script src="<?php echo base_url('assets/js/custom.js');?>"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url('assets/js/theme.init.js');?>"></script>
		
		<?php if (isset($GLOBALS['page_script'])): ?>
			<!-- Page specific customizations -->
			<script>
				<?php echo $GLOBALS['page_script']; ?>
			</script>
		<?php endif; ?>
			
		<!-- Page specific Javascript code-->
                <?php 
                foreach ($js_templates as $js)
                {
                    echo $js;
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
