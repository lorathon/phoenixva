<!DOCTYPE html>
<html>
<head>
<title><?php echo $meta_title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Twitter Bootstrap -->
<link href="<?php echo base_url('css/bootstrap.min.css'); ?>"
	rel="stylesheet" media="screen">

<!-- jQuery -->
<script type="text/javascript"
	src="<?php echo base_url('assets/grocery_crud/js/jquery-1.10.2.min.js'); ?>"></script>

<!-- jQuery_UI -->
<link type="text/css" rel="stylesheet"
	href="<?php echo base_url('assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css'); ?>" />
<script
	src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js'); ?>"
	type="text/javascript"></script>

<!-- Grocery CRUD -->
<?php if(isset($css_files)) : ?>
	<?php foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php endforeach; ?>
<?php endif; ?>
<?php if(isset($js_files)) : ?>
	<?php foreach($js_files as $file): ?>
		<script src="<?php echo $file; ?>" type="text/javascript"></script>
	<?php endforeach; ?>
<?php endif; ?>

</head>
<body>
	<!-- START: admin/components/page_navigation_bar -->

	<div class="navbar navbar-static-top navbar-inverse">
		<div class="navbar-inner">
			<a class="brand" href="<?php echo site_url(''); ?>"><?php echo $meta_title; ?></a>
			<ul class="nav">
				<li class="active"><a
					href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
				<li><?php echo anchor('admin/page', 'Pages'); ?></li>
				<li><?php echo anchor('admin/article', 'News'); ?></li>
				<li class="dropdown"><a data-toggle="dropdown"
					class="dropdown-toggle" href="#">Users<strong class="caret"></strong></a>
					<ul class="dropdown-menu">
						<li><?php echo anchor('admin/user', 'User Manager'); ?></li>
						<li><?php echo anchor('admin/user/new_users', 'New Users'); ?></li>
						<li class="divider" />
						<li class="nav-header">User Items</li>
						<li />
						<li><?php echo anchor('admin/rank', 'Ranks'); ?></li>
					</ul></li>
				<li></li>
				<li><?php echo anchor('admin/award', 'Awards'); ?></li>
				<li></li>
			<li></li>
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">Operations<strong class="caret"></strong></a>
				<ul class="dropdown-menu">
					<li></li>
					<li><?php echo anchor('admin/aircraft', 'Aircraft')?></li>
				</ul></li>
			<li></li>
			<li><?php echo anchor('admin/airline', 'Airlines')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/regional', 'Airlines Regional')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/airport', 'Airports')?></li>
			<li></li>
			<ul></ul>
			<li></li>
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">Schedules<strong class="caret"></strong></a>
				<ul class="dropdown-menu">
					<li></li>
					<li><?php echo anchor('admin/schedule_active', 'Active'); ?></li>
				</ul></li>
			<li class="divider"></li>
			<li></li>
			<li><?php echo anchor('admin/schedule_pending', 'Pending'); ?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/schedule_archive', 'Archive'); ?></li>
			<li></li>
			<ul></ul>
			<li></li>
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">Pireps<strong class="caret"></strong></a>
				<ul class="dropdown-menu">
					<li></li>
					<li><?php echo anchor('admin/dashboard', 'All Pending Pireps')?></li>
				</ul></li>
			<li class="divider"></li>
			<li class="nav-header">Crew Center Pireps</li>
			<li></li>
			<li><?php echo anchor('admin/dashboard', 'EDDT Pireps')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/dashboard', 'EGLL Pireps')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/dashboard', 'KJFK Pireps')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/dashboard', 'KLAX Pireps')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/dashboard', 'KSTL Pireps')?></li>
			<li></li>
			<ul></ul>
			<li></li>
			<li class="dropdown"><a data-toggle="dropdown"
				class="dropdown-toggle" href="#">Finances<strong class="caret"></strong></a>
				<ul class="dropdown-menu">
					<li></li>
					<li><?php echo anchor('admin/finance_prices', 'Pricing')?></li>
				</ul></li>
			<li></li>
			<li><?php echo anchor('admin/finance_pireps_fees', 'PIREP Fees')?></li>
			<li></li>
			<li></li>
			<li><?php echo anchor('admin/finance_fuel_price', 'Fuel Price')?></li>
			<li></li>
			<ul></ul>
			<li></li>
			<ul></ul>
			<ul class="nav pull-right">
				<li><a href="#">Settings</a></li>
			</ul>
			</ul>
			
		</div>
	</div>
	<!-- END: admin/components/page_navigation_bar -->

	<div class="container-fluid">
		<div class="row-fluid">
			<!-- Main Column -->
			<div class="span1"></div>
			<div class="span10">
				<?php echo set_breadcrumb(); ?>
			</div>
			<div class="span1"></div>
		</div>
		<?php if($alert == TRUE)
        { ?>
		<div class="row-fluid">
			<div class="span1"></div>
			<div class="alert alert-&lt;?php echo $alert_type?&gt; span9">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong><?php echo ucfirst($alert_type)?>!</strong>
				<?php echo $alert_message ?>
			</div>
			<div class="span1"></div>
		</div>
		<?php } ?>
	</div>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span9">
			<?php echo $view_output; ?>
		</div>
		<!-- Sidebar -->
		<div class="span1">
			<section>
				<i class="icon-user"></i>
				<?php echo $userdata['name']; ?>
				<br>
				<?php echo anchor('/auth/logout/', '<i class="icon-off"></i> Logout'); ?>
			</section>
			<hr />
			<?php echo $view_sidebar; ?>
		</div>
		<div class="span1"></div>
	</div>
	<!-- END: admin/_layout_main.php -->
	<!-- START: admin/components/page_footer.php -->
	<!--<script src="http://code.jquery.com/jquery.js"></script>-->
	<script src="&lt;?php echo base_url('js/bootstrap.min.js')?&gt;"
		type="text/javascript"></script>
</body>
</html>