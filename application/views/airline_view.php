<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>

<div class="container">
    <div class="row">
	<div class="col-md-8">
	    <div class="featured-box featured-box-green">
		<div class="box-content">
		    
		</div>
	    </div>
	</div>
	<div class="col-md-4">
	    
	    <!-- If user is admin show options -->
	    <?php if ($show_admin): ?>
		<div class="featured-box featured-box-red">
		    <div class="box-content">
			<h2>Airline Admin</h2>
			<ul class="nav nav-pills">			    
			    <li role="presentation">
				<?php echo anchor("/private/airlines/edit-airline/" . $airline->id, 'Edit Airline'); ?>
			    </li>
			</ul>
		    </div>
		</div>
	    <?php endif; ?>
	    <!-- end: admin options -->
	    
	<div class="featured-box featured-box-blue">
	    <div class="box-content">
		<h2>Airline Details</h2>
		<table class="table table-hover table-condensed">
		    <tr>
			<td>IATA: </td>
			<td><?php echo $airline->iata ?></td>
		    </tr>
		    <tr>
			<td>ICAO: </td>
			<td><?php echo $airline->icao; ?></td>
		    </tr>
		    <tr>
			<td>Name: </td>
			<td><?php echo $airline->name; ?></td>
		    </tr>
		    <tr>
			<td>Name: </td>
			<td><?php echo $airline->name; ?></td>
		    </tr>
		    <tr>
			<td>Active: </td>
			<td><?php echo $airline->active; ?></td>
		    </tr>
		    <tr>
			<td>Category: </td>
			<td><?php echo $airline->get_category()->description; ?></td>
		    </tr>
		    
		</table>
	    </div>
	</div>
	</div>
    </div>    
</div>