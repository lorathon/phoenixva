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

$dep_airport = array(
    'name' => 'dep_airport',
    'id' => 'dep_airport',
    'class' => $field_class,
    'placeholder' => 'Select Departure Airport'
);

$dep_airport_id = array(
    'name' => 'dep_airport_id',
    'id' => 'dep_airport_id',
    'class' => $field_class,
    'type' => 'hidden'
);

$arr_airport = array(
    'name' => 'arr_airport',
    'id' => 'arr_airport',
    'class' => $field_class,
    'placeholder' => 'Select Arrival Airport'
);

$arr_airport_id = array(
    'name' => 'arr_airport_id',
    'id' => 'arr_airport_id',
    'class' => $field_class,
    'type' => 'hidden'
);

$operator = array(
    'name' => 'operator',
    'id' => 'operator',
    'class' => $field_class,
    'placeholder' => 'Select Operating Airline'
);

$operator_id = array(
    'name' => 'operator_id',
    'id' => 'operator_id',
    'class' => $field_class,
    'type' => 'hidden'
);

$carrier = array(
    'name' => 'carrier',
    'id' => 'carrier',
    'class' => $field_class,
    'placeholder' => 'Select Carrier Airline'
);

$carrier_id = array(
    'name' => 'carrier_id',
    'id' => 'carrier_id',
    'class' => $field_class,
    'type' => 'hidden'
);
?>

<div class="container">

    <?php if (isset($schedules)) : ?>

        <div class="row">
    	<!--  Schedule Search Form -->
    	<div class="col-md-12">
    	    <div class="toogle active" data-plugin-toggle>
    		<section class="toggle <?php if($search) echo 'active'; ?>">
    		    <label>Schedule Search</label>
    		    <div class="toggle-content">
			    <?php echo form_open_multipart('private/schedules/search-schedules', $form_attributes); ?>

			    <?php echo form_input($operator_id); ?>
			    <?php echo form_input($carrier_id); ?>
			    <?php echo form_input($dep_airport_id); ?>
			    <?php echo form_input($arr_airport_id); ?>

    			<div class="form-group">
				<?php echo form_label('Operator Airline', $operator['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($operator); ?></div>
    			</div>

    			<div class="form-group">
				<?php echo form_label('Carrier Airline', $carrier['id'], $label_attributes); ?>
    			    <div class="col-md-6"><?php echo form_input($carrier); ?></div>
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
    			    <div class="col-md-12">
				    <?php echo form_submit('save', 'Search', 'class = "btn btn-primary btn-block"'); ?>
    			    </div>
    			</div>

			    <?php echo form_close(); ?>
    		    </div>
    		</section>
    	    </div>
    	</div>
        </div>
        <div class="row">
    	<!-- Search Results -->
    	<div class="col-md-12">
    	    <div class="toogle" data-plugin-toggle>
    		<section class="toggle <?php if(!$search) echo 'active'; ?>">
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

	<?php else : ?>

    	<div class="row">
    	    <!-- View bids results -->
    	</div>

	<?php endif; ?>

    </div>