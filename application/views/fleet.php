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
		<?php if (uri_string() == 'fleet/view/' . $aircraft->id . '/airlines'): ?>
		    class="active"
		    <?php endif; ?> >
			<?php echo anchor('/fleet/view/' . $aircraft->id . '/airlines', 'Airlines'); ?> 
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
				<td><?php echo airline($row); ?></td>
				<td data-toggle="tooltip" title="<?php echo $row->get_category()->description; ?>"><?php echo $row->category; ?></td>
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
			    <td><?php echo $aircraft->name; ?></td>
			</tr>
			<tr>
			    <td>Standard Seating: </td>
			    <td><?php echo '(1st)'.$aircraft->pax_first . ' | (bus)' . $aircraft->pax_business . ' | (eco)' . $aircraft->pax_economy; ?></td>
			</tr>	
			<tr>
			    <td>Standard Payload: </td>
			    <td><?php echo $aircraft->payload; ?></td>
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