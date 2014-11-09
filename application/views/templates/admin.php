<!DOCTYPE html>
<html>
<head>
<title><?php echo $meta_title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Twitter Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

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

	<nav class="navbar navbar-static-top navbar-inverse" role="navigation">
		<div class="container">
			<a class="navbar-brand" href="<?php echo site_url(''); ?>"><?php echo $meta_title; ?></a>
			<ul class="nav navbar-nav">
				<li class="active"><a
					href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
				<li><?php echo anchor('admin/page', 'Pages'); ?></li>
				<li><?php echo anchor('admin/new', 'News'); ?></li>
				<li><?php echo anchor('admin/event', 'Events'); ?></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">Users<strong class="caret"></strong></a>
					<ul class="dropdown-menu" role="menu">
						<li><?php echo anchor('admin/users', 'User Manager'); ?></li>
						<li><?php echo anchor('admin/users/new', 'New Users'); ?></li>
						<li class="divider" />
						<li class="nav-header">User Items</li>
						<li><?php echo anchor('admin/ranks', 'Ranks'); ?></li>
						<li><?php echo anchor('admin/awards', 'Awards'); ?></li>
					</ul>
				</li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">Operations<strong class="caret"></strong></a>
					<ul class="dropdown-menu" role="menu">
						<li><?php echo anchor('admin/aircraft', 'Aircraft')?></li>
						<li><?php echo anchor('admin/airlines', 'Airlines')?></li>
						<li><?php echo anchor('admin/airports', 'Airports')?></li>
					</ul>
				</li>
				<li><?php echo anchor('admin/schedules', 'Schedules'); ?></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">Pireps<strong class="caret"></strong></a>
					<ul class="dropdown-menu">
						<li><?php echo anchor('admin/pireps', 'All Pending Pireps')?></li>
					</ul>
				</li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">Finances<strong class="caret"></strong></a>
					<ul class="dropdown-menu">
						<li><?php echo anchor('admin/finance/prices', 'Pricing')?></li>
						<li><?php echo anchor('admin/finance/pirep_fees', 'PIREP Fees')?></li>
						<li><?php echo anchor('admin/finance/fuel_price', 'Fuel Price')?></li>
					</ul>
				</li>
			</ul>
			<ul class="nav pull-right">
				<li><a href="#">Settings</a></li>
			</ul>
		</div>
	</nav>
	<!-- END: admin/components/page_navigation_bar -->

	<div class="container-fluid">
		<?php if($alert == TRUE)
        { ?>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="alert alert-<?php echo $alert_type?> col-md-9">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong><?php echo ucfirst($alert_type)?>!</strong>
				<?php echo $alert_message ?>
			</div>
			<div class="col-md-1"></div>
		</div>
		<?php } ?>
	</div>
	<div class="row">
		<div class="col-md-offset-1 col-md-8">
			<?php echo $view_output; ?>
		</div>
		<!-- Sidebar -->
		<div class="col-md-2">
				<i class="icon-user"></i>
				<?php echo $userdata['name']; ?>
				<br>
				<?php echo anchor('/auth/logout/', '<i class="icon-off"></i> Logout'); ?>
			<hr />
			<?php //echo $view_sidebar; ?>
		</div>
	</div>
	<!-- END: admin/_layout_main.php -->
	<!-- START: admin/components/page_footer.php -->
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>