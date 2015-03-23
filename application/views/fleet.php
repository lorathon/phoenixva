<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>
<div class="container">
    <?php if(isset($fleet)) : ?>
    
    <!-- Start: Category View -->
    <div class="row">
	<ul class="nav nav-tabs">	    
	    <?php foreach($fleet_cat as $key => $value) : ?> 		
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/' . $key): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/' . $key, 'CAT ' . $value); ?> 
		</li>	    
	    <?php endforeach; ?>		
	</ul>            
    </div>
    <div class="row">
	<div class="col-md-12">
	    <div class="table-responsive">
		<table class="table table-hover mb-none">
		    <thead>
			<tr>
			    <th>Airframe</th>
			    <th>Name</th>
			    <th>Sub Chart</th>
			    <th>Total Schedules</th>
			    <th>Total PIREPs</th>
			    <?php if($show_admin) : ?>
			    <th>Actions</th>
			    <?php endif; ?>
			</tr>
		    </thead>
		    <tbody>
			<?php if($fleet) : ?>
			<?php foreach ($fleet as $row): ?>
			    <tr>
				<td><?php echo anchor('fleet/view/' . $row->id, $row->equip); ?></td>
				<td><?php echo $row->name; ?></td>
				<td><?php echo anchor('fleet/view-sub/' . $row->aircraft_sub_id, $row->aircraft_sub_id); ?></td>
				<td><?php echo $row->flight_count; ?></td>
				<td><?php echo $row->total_pireps; ?></td>
				<?php if($show_admin) : ?>
				<td align="center">					
				    <?php echo anchor('private/fleet/edit-aircraft/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>					
				</td>
				<?php endif; ?>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no aircraft in this category.</td>
			    </tr>
			<?php endif; ?>
		    </tbody>
		</table>
	    </div>
	</div>	
    </div>
    <!-- End: Category View -->
    
    <?php elseif(isset($aircraft)) : ?>
    
    <!-- Start: Aircraft Detail -->
    <div class="row">
	<ul class="nav nav-tabs">		
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $aircraft->id . '/pireps'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $aircraft->id . '/pireps', 'PIREPs'); ?> 
		</li>
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $aircraft->id . '/flights'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $aircraft->id . '/flights', 'Flights'); ?> 
		</li>
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $aircraft->id . '/main'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $aircraft->id . '/main', 'Main Airlines'); ?> 
		</li>	
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $aircraft->id . '/regional'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $aircraft->id . '/regional', 'Regional Airlines'); ?> 
		</li>	
	</ul>            
    </div>
    <div class="row">
	<div class="col-md-8">
	    <?php if(isset($flights)) : ?>
	    <!-- Start: Flights Table (pireps and flights) -->
	    
	    <div class="table-responsive">
		<table class="table table-hover mb-none">
		    <thead>
			<tr>
			    <th>Carrier</th>
			    <th>Operator</th>
			    <th>Flight Number</th>
			    <th>Dep IATA</th>
			    <th>Arr IATA</th>
			</tr>
		    </thead>
		    <tbody>
			<?php if($flights) : ?>
			<?php foreach ($flights as $row): ?>
			    <tr>
				<td><?php echo $row->carrier; ?></td>
				<td><?php echo $row->operator; ?></td>
				<td><?php echo $row->flight_num; ?></td>
				<td><?php echo $row->dep_airport; ?></td>
				<td><?php echo $row->arr_airport; ?></td>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no records in this category.</td>
			    </tr>
			<?php endif; ?>
		    </tbody>
		</table>
	    </div>	    	    
	    
	    <!-- End: Flight Table -->
	    <?php else : ?>
	    <!-- Start: Airlines Table (main and regional) -->
	    
	    <div class="table-responsive">
		<table class="table table-hover mb-none">
		    <thead>
			<tr>
			    <th>IATA</th>
			    <th>ICAO</th>
			    <th>Name</th>
			    <th>Category</th>
			</tr>
		    </thead>
		    <tbody>
			<?php if($airlines) : ?>
			<?php foreach ($airlines as $row): ?>
			    <tr>
				<td><?php echo $row->iata; ?></td>
				<td><?php echo $row->icao; ?></td>
				<td><?php echo $row->name; ?></td>
				<td><?php echo $row->category; ?></td>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no airlines in this category.</td>
			    </tr>
			<?php endif; ?>
		    </tbody>
		</table>
	    </div>	    
	    
	    <!-- End: Airline Table -->
	    <?php endif; ?>
	</div>
	<div class="col-md-4">
	    
	    <!-- Start: Admin Options -->
	    <?php if($show_admin) : ?>
	    <div class="featured-box featured-box-red">
		<div class="box-content">
		    <h2>Aircraft Admin</h2>
		    <ul class="nav nav-pills">
			<li role="presentation">
			    <?php echo anchor("/private/fleet/edit-aircraft/" . $aircraft->id, 'Edit Aircraft'); ?>
			</li>
		    </ul>
		</div>
	    </div>
	    <?php endif; ?>
	    <!-- End: Admin Options -->
	    
	    <!-- Start: Aircraft Detail -->
	    <div class="featured-box featured-box-blue">
		<div class="box-content">
		    <h2>Aircraft Detail</h2>
		    <table class="table table-hover table-condensed">
			<tr>
			    <td>Name: </td>
			    <td><?php echo $aircraft->name; ?></td>
			</tr>
			<tr>
			    <td>Airframe: </td>
			    <td><?php echo $aircraft->equip; ?></td>
			</tr>
			<tr>
			    <td>Pax: </td>
			    <td><?php echo '(1st)'.$aircraft->pax_first . ' -(bus)' . $aircraft->pax_business . ' (eco)' . $aircraft->pax_economy; ?></td>
			</tr>	
			<tr>
			    <td>Cargo: </td>
			    <td><?php echo $aircraft->max_cargo; ?></td>
			</tr>
			<tr>
			    <td>Range: </td>
			    <td><?php echo $aircraft->max_range; ?></td>
			</tr>
			<tr>
			    <td>OEW: </td>
			    <td><?php echo $aircraft->oew; ?></td>
			</tr>
			<tr>
			    <td>MZFW: </td>
			    <td><?php echo $aircraft->mzfw; ?></td>
			</tr>
			<tr>
			    <td>MLW: </td>
			    <td><?php echo $aircraft->mlw; ?></td>
			</tr>
			<tr>
			    <td>MTOW: </td>
			    <td><?php echo $aircraft->mtow; ?></td>
			</tr>
			<tr>
			    <td>Main Airlines: </td>
			    <td><?php echo $aircraft->carrier_count; ?></td>
			</tr>
			<tr>
			    <td>Regional Airlines: </td>
			    <td><?php echo $aircraft->operator_count; ?></td>
			</tr>
			<tr>
			    <td>Scheduled Flights: </td>
			    <td><?php echo $aircraft->flight_count; ?></td>
			</tr>
			<tr>
			    <td>Recorded Flights: </td>
			    <td><?php echo $aircraft->total_pireps; ?></td>
			</tr>
			<tr>
			    <td>Recorded Hours: </td>
			    <td><?php echo $aircraft->total_hours; ?></td>
			</tr>
			<tr>
			    <td>Recorded Distance: </td>
			    <td><?php echo $aircraft->total_distance; ?></td>
			</tr>
		    </table>
		</div>
	    </div>
	    <!-- End: Aircraft Detail -->
	    
	</div>
    </div> 
    
    <!-- End: Aircraft Detail -->
    
    <?php else : ?>
    
    <!-- Start: Aircraft Substitution Chart -->
    <div class="row">
	<div class="col-md-12">
	    <div class="table-responsive">
		<table class="table table-hover mb-none">
		    <thead>
			<tr>
			    <th>Equipment</th>
			    <th>Name</th>
			    <th>Sub Chart</th>
			    <th>Total Schedules</th>
			    <th>Total PIREPs</th>
			    <?php if($show_admin) : ?>
			    <th>Actions</th>
			    <?php endif; ?>
			</tr>
		    </thead>
		    <tbody>
			<?php if($sub_fleet) : ?>
			<?php foreach ($sub_fleet as $row): ?>
			    <tr>
				<td><?php echo anchor('fleet/view/' . $row->id, $row->equip); ?></td>
				<td><?php echo $row->name; ?></td>
				<td><?php echo anchor('fleet/view-sub/' . $row->aircraft_sub_id, $row->aircraft_sub_id); ?></td>
				<td><?php echo $row->flight_count; ?></td>
				<td><?php echo $row->total_pireps; ?></td>
				<?php if($show_admin) : ?>
				<td align="center">					
				    <?php echo anchor('private/fleet/edit-aircraft/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>					
				</td>
				<?php endif; ?>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no aircraft in this category.</td>
			    </tr>
			<?php endif; ?>
		    </tbody>
		</table>
	    </div>
	</div>	
    </div>    
    <!-- End: Aircraft Substitution Chart -->
    
    <?php endif; ?>
</div>