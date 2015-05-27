<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);

$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role' => 'form',
    'id' => 'form',
);

$label_attributes = array(
    'class' => 'col-md-3 control-label',
);

$field_class = 'form-control';

if(isset($schedule)) :

$dep_airport = array(
    'name' => 'dep_airport',
    'id' => 'dep_airport',
    'class' => $field_class,
    'value' => set_value('dep_airport', $schedule->dep_airport),
    'placeholder' => 'Select Departure Airport'
);

$dep_airport_id = array(
    'name' => 'dep_airport_id',
    'id' => 'dep_airport_id',
    'class' => $field_class,
    'value' => set_value('dep_airport_id', $schedule->dep_airport_id),
    'type' => 'hidden'
);

$arr_airport = array(
    'name' => 'arr_airport',
    'id' => 'arr_airport',
    'class' => $field_class,
    'value' => set_value('arr_airport', $schedule->arr_airport),
    'placeholder' => 'Select Arrival Airport'
);

$arr_airport_id = array(
    'name' => 'arr_airport_id',
    'id' => 'arr_airport_id',
    'class' => $field_class,
    'value' => set_value('arr_airport_id', $schedule->arr_airport_id),
    'type' => 'hidden'
);

$flight_num = array(
    'name' => 'flight_num',
    'id' => 'flight_num',
    'class' => $field_class,
    'value' => set_value('flight_num', $schedule->flight_num),
    'placeholder' => 'Enter Flight Number (Less Airline Code)'
);

$operator = array(
    'name' => 'operator',
    'id' => 'operator',
    'class' => $field_class,
    'value' => set_value('operator', $schedule->operator),
    'placeholder' => 'Select Operating Airline'
);

$operator_id = array(
    'name' => 'operator_id',
    'id' => 'operator_id',
    'class' => $field_class,
    'value' => set_value('operator_id', $schedule->operator_id),
    'type' => 'hidden'
);

$carrier = array(
    'name' => 'carrier',
    'id' => 'carrier',
    'class' => $field_class,
    'value' => set_value('carrier', $schedule->carrier),
    'placeholder' => 'Select Carrier Airline'
);

$carrier_id = array(
    'name' => 'carrier_id',
    'id' => 'carrier_id',
    'class' => $field_class,
    'value' => set_value('carrier_id', $schedule->carrier_id),
    'type' => 'hidden'
);

$aircraft_sub = array(
    'name' => 'aircraft_sub',
    'id' => 'aircraft_sub',
    'class' => $field_class,
    'value' => set_value('aircraft_sub', $schedule->aircraft_sub),
    'placeholder' => 'Select Airframe Group'
);

$aircraft_sub_id = array(
    'name' => 'aircraft_sub_id',
    'id' => 'aircraft_sub_id',
    'class' => $field_class,
    'value' => set_value('aircraft_sub_id', $schedule->aircraft_sub_id),
    'type' => 'hidden'
);

endif;
?>

<div class="container">

    <?php if (isset($schedules)) : ?>

        <div class="row">
    	<!--  Schedule Search Form -->
    	<div class="col-md-12">
    	    <div class="toogle active" data-plugin-toggle>
    		<section class="toggle <?php if ($search) echo 'active'; ?>">
    		    <label>Schedule Search</label>
    		    <div class="toggle-content">
			    <?php echo form_open_multipart('private/schedules/search-schedules', $form_attributes); ?>

			    <?php echo form_input($operator_id); ?>
			    <?php echo form_input($carrier_id); ?>
			    <?php echo form_input($dep_airport_id); ?>
			    <?php echo form_input($arr_airport_id); ?>
			    <?php echo form_input($aircraft_sub_id); ?>

    			<div class="form-group">
				<?php echo form_label('Operator Airline', $operator['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($operator); ?></div>
    			</div>

    			<div class="form-group">
				<?php echo form_label('Carrier Airline', $carrier['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($carrier); ?></div>
    			</div>
			
			<div class="form-group">
				<?php echo form_label('Flight Number', $flight_num['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($flight_num); ?></div>
    			</div>			

    			<div class="form-group">
				<?php echo form_label('Departure Airport', $dep_airport['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($dep_airport); ?></div>
    			</div>

    			<div class="form-group">
				<?php echo form_label('Arrival Airport', $arr_airport['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($arr_airport); ?></div>
    			</div>
			
			<div class="form-group">
				<?php echo form_label('Airframe Group', $aircraft_sub['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($aircraft_sub); ?></div>
    			</div>

    			<div class="form-group">
    			    <div class="col-md-8">
				<?php echo form_submit('save', 'Search', 'class = "btn btn-info btn-block"'); ?>
    			    </div>
			    <div class="col-md-4">
				<?php echo form_reset('reset', 'Reset', 'class = "btn btn-danger btn-block"'); ?>
    			    </div>
    			</div>

			    <?php echo form_close(); ?>
    		    </div>
    		</section>
    	    </div>
    	</div>
        </div>
        <div class="row">
	    <?php if(! $search) : ?>
    	<!-- Search Results -->
    	<div class="col-md-12">
    	    <div class="toogle" data-plugin-toggle>
    		<section class="toggle <?php if (!$search) echo 'active'; ?>">
    		    <label>Search Results</label>
    		    <div class="toggle-content">
    			<div class="table-responsive">
    			    <table class="table table-hover mb-none">
    				<thead>
    				    <tr>
    					<th>Flight Number</th>
    					<th>Departure</th>
    					<th>Arrival</th>
    					<th>Aircraft</th>
    					<th>Actions</th>
    				    </tr>
    				</thead>
    				<tbody>
					<?php if ($schedules) : ?>
					    <?php foreach ($schedules as $row): ?>
	    				    <tr>
	    					<td><?php echo $row->get_flightnumber(); ?></td>
	    					<td><?php echo airport($row->get_airport(FALSE)); ?></td>
	    					<td><?php echo airport($row->get_airport(TRUE)); ?></td>   
	    					<td><?php echo $row->get_airframe()->icao; ?></td>
	    					<td align="center">					
							<?php echo anchor('private/schedules/create-bid/' . $userdata['user_id'] . '/' . $row->id, '<i class="fa fa-pencil"></i> Bid Flight', button('info')); ?>					
	    					</td>
	    				    </tr>
					    <?php endforeach; ?>
					<?php else : ?>
					    <tr>
						<td colspan="5">No schedules have been located.</td>
					    </tr>
					<?php endif; ?>
    				</tbody>
    			    </table>
    			</div>
    		    </div>
    		</section>
    	    </div>    		
    	</div>
	<?php endif; ?>

	<?php else : ?>
	
	<?php $own_bids = ($user->id == $userdata['user_id']); ?>

    	<div class="row">
    	    <!-- View bids results -->
    	    <div class="col-md-12">
    		<div class="table-responsive">
    		    <table class="table table-hover mb-none" id="bidTable">
    			<thead>
    			    <tr>
    				<th>Flight Number</th>
    				<th>Departure</th>
    				<th>Arrival</th>
    				<th>Aircraft</th>
    				<th>Actions</th>
    			    </tr>
    			</thead>
    			<tbody>
				<?php if ($bids) : ?>
				    <?php foreach ($bids as $row): ?>
	    			    <tr id="item-<?php echo $row->id; ?>">
	    				<td><?php echo $row->get_flightnumber(); ?></td>
	    				<td><?php echo airport($row->get_airport(FALSE)); ?></td>
	    				<td><?php echo airport($row->get_airport(TRUE)); ?></td>   
	    				<td><?php echo $row->get_airframe()->icao; ?></td>
	    				<td align="center">
					    <?php if($show_admin || $own_bids) : ?>
						<?php echo anchor('private/schedules/delete-bid/' . $user->id . '/' . $row->id, '<i class="fa fa-trash"></i> Delete', button_delete('danger')); ?>					
					    <?php endif; ?>
					</td>
	    			    </tr>
				    <?php endforeach; ?>
				<?php else : ?>
				    <tr>
					<td colspan="5">No schedules have been located.</td>
				    </tr>
				<?php endif; ?>
    			</tbody>
    		    </table>

    		</div>    		
    	    </div>
    	</div>

	<?php endif; ?>

    </div>