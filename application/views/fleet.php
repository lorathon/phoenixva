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
			    <th>Sub Chart</th>
			    <th>Turboprop</th>
			    <th>Jet</th>
			    <th>Regional</th>
			    <th>Wide Body</th>
			    <th>Helicopter</th>
			</tr>
		    </thead>
		    <tbody>
			<?php if($fleet) : ?>
			<?php foreach ($fleet as $row): ?>
			    <tr>
				<td><?php echo anchor('fleet/view/' . $row->id, $row->name); ?></td>
				<td><?php echo anchor('fleet/view-sub/' . $row->aircraft_sub_id, $row->aircraft_sub_id); ?></td>
				<td><?php if($row->turboprop == 1): ?><i class="fa fa-check"></i><?php endif ?></td>
				<td><?php if($row->jet == 1): ?><i class="fa fa-check"></i><?php endif ?></td>
				<td><?php if($row->regional == 1): ?><i class="fa fa-check"></i><?php endif ?></td>
				<td><?php if($row->widebody == 1): ?><i class="fa fa-check"></i><?php endif ?></td>
				<td><?php if($row->helicopter == 1): ?><i class="fa fa-check"></i><?php endif ?></td>
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
    
    <?php elseif(isset($airframe)) : ?>
    
    <!-- Start: Aircraft Detail -->
    <div class="row">
	<ul class="nav nav-tabs">		
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $airframe->id . '/pireps'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $airframe->id . '/pireps', 'PIREPs'); ?> 
		</li>
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $airframe->id . '/flights'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $airframe->id . '/flights', 'Flights'); ?> 
		</li>
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $airframe->id . '/airlines'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $airframe->id . '/airlines', 'Airlines'); ?> 
		</li>	
		<li role="presentation" 
		<?php if (uri_string() == 'fleet/view/' . $airframe->id . '/aircraft'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $airframe->id . '/aircraft', 'Aircraft'); ?> 
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
	    <?php elseif(isset($aircraft)) : ?>
	    <!-- Start: Airline Aircraft Table -->
	    
	    <div class="table-responsive">
		<table class="table table-hover mb-none">
		    <thead>
			<tr>
			    <th>Airline</th>
			    <th>Schedule Count</th>
			    <th>Pirep Count</th>
			    <th>Total Hours</th>
			</tr>
		    </thead>
		    <tbody>
			<?php if($aircraft) : ?>
			<?php foreach ($aircraft as $row): ?>
			    <tr>
				<td><?php echo $row->carrier; ?></td>
				<td><?php echo $row->operator; ?></td>
				<td><?php echo $row->flight_num; ?></td>
				<td><?php echo $row->dep_airport; ?></td>
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
	    
	    <!-- End: Airline Aircraft Table -->
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
				<td><?php echo airline($row); ?></td>
				<td><?php echo $row->get_category()->value; ?></td>
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
	    <!-- Start: Aircraft Detail -->
	    <div class="featured-box featured-box-blue">
		<div class="box-content">
		    <h2>Airframe Detail</h2>
		    <table class="table table-hover table-condensed">
			<tr>
			    <td>Name: </td>
			    <td><?php echo $airframe->name; ?></td>
			</tr>
			<tr>
			    <td>Standard Seating: </td>
			    <td><?php echo '(1st)'.$airframe->pax_first . ' | (bus)' . $airframe->pax_business . ' | (eco)' . $airframe->pax_economy; ?></td>
			</tr>	
			<tr>
			    <td>Standard Payload: </td>
			    <td><?php echo $airframe->payload; ?></td>
			</tr>
			<tr>
			    <td>Range: </td>
			    <td><?php echo $airframe->max_range; ?></td>
			</tr>
			<tr>
			    <td>OEW: </td>
			    <td><?php echo $airframe->oew; ?></td>
			</tr>
			<tr>
			    <td>MZFW: </td>
			    <td><?php echo $airframe->mzfw; ?></td>
			</tr>
			<tr>
			    <td>MLW: </td>
			    <td><?php echo $airframe->mlw; ?></td>
			</tr>
			<tr>
			    <td>MTOW: </td>
			    <td><?php echo $airframe->mtow; ?></td>
			</tr>
		    </table>
		</div>
	    </div>
	    <!-- End: Aircraft Detail -->
	    
	    <!-- Start:  Category Legend -->
	    <?php if(isset($airlines)) : ?>
	    <div class="featured-box featured-box-green">
		<div class="box-content">
		    <h2>Category Legend</h2>
		    <table class="table table-hover table-condensed">
			<?php if($airline_categories) : ?>
			<?php foreach ($airline_categories as $row): ?>
			    <tr>
				<td><?php echo $row->value; ?></td>
				<td><?php echo $row->description; ?></td>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="2"></td>
			    </tr>
			<?php endif; ?>
		    </table>
		</div>
	    </div>
	    <?php endif; ?>
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
			    <th>Airframe</th>
			    <th>OEW</th>
			    <th>MZFW</th>
			    <th>MTOW</th>
			    <th>MLW</th>
			    <th>Range</th>
			</tr>
		    </thead>
		    <tbody>
			<?php if($sub_fleet) : ?>
			<?php foreach ($sub_fleet as $row): ?>
			    <tr>
				<td><?php echo anchor('fleet/view/' . $row->id, $row->name); ?></td>
				<td><?php echo $row->oew; ?></td>
				<td><?php echo $row->mzfw; ?></td>
				<td><?php echo $row->mtow; ?></td>
				<td><?php echo $row->mlw; ?></td>
				<td><?php echo $row->max_range; ?></td>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no airframes in this category.</td>
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