<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>

<div class="container">
    
    <?php if(isset($airlines)) : ?>
    
    <div class="row">
	<ul class="nav nav-tabs">		
	    <li role="presentation" 
	    <?php if (uri_string() == 'airlines/main'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airlines/main', 'Main Carriers'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airlines/regional'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airlines/regional', 'Regional Carriers'); ?> 
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
			    <th>Logo</th>
			    <?php if($show_admin) : ?>
			    <th>Actions</th>
			    <?php endif; ?>
			</tr>
		    </thead>
		    <tbody>
			<?php if($airlines) : ?>
			<?php foreach ($airlines as $row): ?>
			    <tr>
				<td><?php echo $row->icao; ?></td>
				<td><?php echo $row->iata ?></td>
				<td><?php echo airline($row); ?></td>   
				<td>IMAGE</td>
				<?php if($show_admin) : ?>
				<td align="center">					
				    <?php echo anchor('private/airlines/edit-airline/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>					
				</td>
				<?php endif; ?>
			    </tr>
			<?php endforeach; ?>
			<?php else : ?>
			    <tr>
				<td colspan="5">There are no airlines of this airline type.</td>
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
	    <?php if (uri_string() == 'airlines/view/' . $airline->id . '/fleet'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airlines/view/' . $airline->id . '/fleet', 'Fleet'); ?> 
	    </li>	
	    <li role="presentation" 
	    <?php if (uri_string() == 'airlines/view/' . $airline->id . '/destinations'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airlines/view/' . $airline->id . '/destinations', 'Destinations'); ?> 
	    </li>
	</ul> 
    </div>
    <div class="row">	
	<div class="col-md-8">
	    <div class="table-responsive">		
		<?php if(isset($fleet)) : ?>
		
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Schedules</th>
                                <th>Pireps</th>
                                <th>Hours</th>
				<?php if($show_admin) : ?>
				    <th>Actions</th>
				<?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
			    <?php if($fleet) : ?>
                            <?php foreach ($fleet as $row): ?>			    
                                <tr>
				    <td><?php echo $row->get_airframe()->name; ?></td>
                                    <td><?php echo $row->total_schedules; ?></td>
                                    <td><?php echo $row->total_flights; ?></td>
                                    <td><?php echo $row->total_hours; ?></td> 
				    <?php if($show_admin) : ?>
				    <td align="center">
					<?php echo anchor('private/airlines/edit-aircraft/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
				    </td>
				    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
			    <?php else : ?>
				<tr>
				    <td colspan="5">There are no aircraft in fleet.</td>
				</tr>
			    <?php endif; ?>
                        </tbody>
                    </table>
		
		<?php elseif (isset($airports)) : ?>
		
		    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>IATA</th>
                                <th>ICAO</th>
                                <th>Country</th>
                            </tr>
                        </thead>
                        <tbody>
			    <?php if($airports) : ?>
                            <?php foreach ($airports as $row): ?>
                                <tr>
				    <td><?php echo airport($row); ?></td>
                                    <td><?php echo $row->iata; ?></td>
                                    <td><?php echo $row->icao; ?></td>
                                    <td><?php echo $row->country_name; ?></td> 
                                </tr>
                            <?php endforeach; ?>
			    <?php else : ?>
				<tr>
				    <td colspan="5">There are no destinations.</td>
				</tr>
			    <?php endif; ?>
                        </tbody>
                    </table>
		
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
    
    <?php endif; ?>
    
</div>