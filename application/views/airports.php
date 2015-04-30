<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>

<div class="container">
    
    <?php if(isset($airports)) : ?>
    
    <div class="row">
	<ul class="nav nav-tabs">		
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/land'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/land', 'Land'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/heli'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/heli', 'Helicopter'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/sea'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/sea', 'Sea'); ?> 
	    </li>
	</ul>  
    </div>
    <div class="row">
	<div class="col-md-12">
	    <div class="table-responsive">
		<table class="table table-hover mb-none">
		    <thead>
			<tr>
			    <th>ICAO</th>
			    <th>IATA</th>
			    <th>Name</th>
			    <th>City</th>
			    <th>State</th>
			    <th>Country</th>
			    <th>Region</th>
			    <?php if($show_admin) : ?>
			    <th>Actions</th>
			    <?php endif; ?>
			</tr>
		    </thead>
		    <tbody>
			<?php if($airports) : ?>
			<?php foreach ($airports as $row): ?>
			    <tr>
				<td><?php echo $row->icao; ?></td>
				<td><?php echo $row->iata ?></td>
				<td><?php echo anchor('airports/view/'.$row->id, $row->name); ?></td>   
				<td><?php echo $row->city; ?></td>
				<td><?php echo $row->state_code; ?></td>
				<td><?php echo $row->country_code; ?></td>
				<td><?php echo $row->region_name; ?></td>
				<?php if($show_admin) : ?>
				<td align="center">					
				    <?php echo anchor('private/airports/edit-airport/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>					
				</td>
				<?php endif; ?>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no airports of this airport group.</td>
			    </tr>
			<?php endif; ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>

    <?php else : ?>

    <div class="row">
	<ul class="nav nav-tabs">
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/view/' . $airport->id . '/airlines'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/view/' . $airport->id . '/airlines', 'Airlines'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/view/' . $airport->id . '/departures'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/view/' . $airport->id . '/departures', 'Departures'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/view/' . $airport->id . '/arrivals'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/view/' . $airport->id . '/arrivals', 'Arrivals'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airports/view/' . $airport->id . '/map'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airports/view/' . $airport->id . '/map', 'Map'); ?> 
	    </li>
	</ul> 
    </div>
    <div class="row">	
	<div class="col-md-8">
	    <div class="table-responsive">
		
		<?php if(isset($airlines)) : ?>
		
		    <!-- Start: Airlines -->
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>Logo</th>
				<th>IATA</th>
				<th>IACO</th>
                                <th>Name</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
			    <?php if($airlines) : ?>
                            <?php foreach ($airlines as $row): ?>			    
                                <tr>
				    <td>IMAGE</td>
				    <td><?php echo $row->iata; ?></td>
				    <td><?php echo $row->icao; ?></td>
                                    <td><?php echo airline($row); ?></td>
                                    <td><?php echo $row->get_category()->description; ?></td>
                                </tr>
                            <?php endforeach; ?>
			    <?php else : ?>
				<tr>
				    <td colspan="5">There are no airlines that fly here.</td>
				</tr>
			    <?php endif; ?>
                        </tbody>
                    </table>
		<!-- End: Airlines -->
		
		<?php elseif (isset($schedules)) : ?>
		
		<!-- Start: Schedules -->
		    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>Flight Number</th>
                                <th>Airline</th>
                                <th>Arrival</th>
                                <th>Aircraft</th>
                            </tr>
                        </thead>
                        <tbody>
			    <?php if($schedules) : ?>
                            <?php foreach ($schedules as $row): ?>
                                <tr>
				    <td><?php echo $row->get_flightnumber(); ?></td>
                                    <td><?php echo airline($row->get_airline()); ?></td>
                                    <td><?php echo airport($row->get_airport(TRUE)); ?></td>
                                    <td></td> 
                                </tr>
                            <?php endforeach; ?>
			    <?php else : ?>
				<tr>
				    <td colspan="5">There are no schedules.</td>
				</tr>
			    <?php endif; ?>
                        </tbody>
                    </table>		
		<!-- End: Schedules -->
		
		<?php else : ?>
		
		<!-- Start: Map -->
		<div id="map-canvas" style="width:100%;height:600px;"></div>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
		
		<script>
		    var map;
		    function initialize() {
			var mapOptions = {
			    zoom: 13,
			    mapTypeId: 'hybrid',
			    streetViewControl: 'FALSE',
			    center: new google.maps.LatLng(<?php echo $airport->lat.', '.$airport->long; ?>)
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),
			    mapOptions);
		    }
		    google.maps.event.addDomListener(window, 'load', initialize);
		</script>			
		<!-- End: Map -->
		
		<?php endif; ?>
		
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
				<?php echo anchor("/private/airports/edit-airport/" . $airline->id, 'Edit Airport'); ?>
			    </li>
			</ul>
		    </div>
		</div>
	    <?php endif; ?>
	    <!-- end: admin options -->
	    
	    <div class="featured-box featured-box-blue">
		<div class="box-content">
		    <h2>Airport Details</h2>
		    <table class="table table-hover table-condensed">
			<tr>
			    <td>IATA: </td>
			    <td><?php echo $airport->iata ?></td>
			</tr>
			<tr>
			    <td>ICAO: </td>
			    <td><?php echo $airport->icao; ?></td>
			</tr>
			<tr>
			    <td>Name: </td>
			    <td><?php echo $airport->name; ?></td>
			</tr>
			<tr>
			    <td>City: </td>
			    <td><?php echo $airport->city; ?></td>
			</tr>
			<tr>
			    <td>State: </td>
			    <td><?php echo $airport->state_code; ?></td>
			</tr>
			<tr>
			    <td>Country: </td>
			    <td><?php echo $airport->country_code; ?></td>
			</tr>
			<tr>
			    <td>Region: </td>
			    <td><?php echo $airport->region_name; ?></td>
			</tr>
			<tr>
			    <td>Type: </td>
			    <td><?php echo ucfirst($airport->port_type); ?></td>
			</tr>
			<tr>
			    <td>Elevation: </td>
			    <td><?php echo $airport->elevation; ?> ft</td>
			</tr>
			<tr>
			    <td>Lattitude: </td>
			    <td><?php echo $airport->lat; ?></td>
			</tr>
			<tr>
			    <td>Lonigtude: </td>
			    <td><?php echo $airport->long; ?></td>
			</tr>

		    </table>
		</div>
	    </div>
	</div>
    </div>    
    
    <?php endif; ?>
    
</div>