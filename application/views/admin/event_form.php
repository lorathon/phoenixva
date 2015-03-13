<?php
$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role'  => 'form',
    'id'    => 'form',
);

$label_attributes = array(
    'class' => 'col-md-3 control-label',    
); 

$field_class = 'form-control';

$id = array(
    'name'	    => 'id',
    'id'	    => 'id',
    'value'	    => set_value($record->id),
    'class'         => $field_class,    
);

$name = array(
    'name'	    => 'name',
    'id'	    => 'name',
    'value'	    => set_value('name', $record->name),
    'class'         => $field_class,
    'placeholder'   => 'Name of event',
    'required'      => 'required'
);

$description = array(
    'name'	    => 'description',
    'id'	    => 'description',
    'value'	    => set_value('description', $record->description),
    'class'         => $field_class,
    'rows'          => 4,
    'placeholder'   => 'Brief description of event',
    'required'      => 'required'
);

$time_start = array(
    'name'	    => 'time_start',
    'id'	    => 'time_start',
    'value'	    => set_value('time_start', $record->time_start),
    'class'         => $field_class,
);

$time_end = array(
    'name'	    => 'time_end',
    'id'	    => 'time_end',
    'value'	    => set_value('time_end', $record->time_end),
    'class'         => $field_class,
);

$event_type_id = array(
    'name'	    => 'event_type_id',
    'id'	    => 'event_type_id',
    'value'	    => set_value('event_type_id', $record->event_type_id),
    'class'         => $field_class,
);

$waiver_js = array(
    'name'	    => 'waiver_js',
    'id'	    => 'waiver_js',
    'value'	    => TRUE,
    'checked'	    => $record->waiver_js ? TRUE : FALSE,
    'class'	    => $field_class,
);

$waiver_cat = array(
    'name'	    => 'waiver_cat',
    'id'	    => 'waiver_cat',
    'value'	    => TRUE,
    'checked'	    => $record->waiver_cat ? TRUE : FALSE,
    'class'	    => $field_class,
);

$airline_id = array(
    'name'	    => 'airline_id',
    'id'	    => 'airline_id',
    'value'	    => set_value('airline_id', $record->airline_id),
    'class'         => $field_class,
);

$airport_id = array(
    'name'	    => 'airport_id',
    'id'	    => 'airport_id',
    'value'	    => set_value('airport_id', $record->airport_id),
    'class'         => $field_class,
);

$aircraft_cat_id = array(
    'name'	    => 'aircraft_cat_id',
    'id'	    => 'aircraft_cat_id',
    'value'	    => set_value('aircraft_cat_id', $record->aircraft_cat_id),
    'class'         => $field_class,
);

if($record->landing_rate == NULL)
    $record->landing_rate = 0;

$landing_rate = array(
    'name'	    => 'landing_rate',
    'id'	    => 'landing_rate',
    'value'	    => set_value('landing_rate', $record->landing_rate),
    'class'         => $field_class,
);

if($record->flight_time == NULL)
    $record->flight_time = 0;

$flight_time = array(
    'name'	    => 'flight_time',
    'id'	    => 'flight_time',
    'value'	    => set_value('flight_time', $record->flight_time),
    'class'         => $field_class,
);

$total_flights = array(
    'name'	    => 'total_flights',
    'id'	    => 'total_flights',
    'value'	    => set_value('total_flights', $record->total_flights),
    'class'         => $field_class,
);

$bonus_1 = array(
    'name'	    => 'bonus_1',
    'id'	    => 'bonus_1',
    'value'	    => set_value('bonus_1', $record->bonus_1),
    'class'         => $field_class,
);

$bonus_2 = array(
    'name'	    => 'bonus_2',
    'id'	    => 'bonus_2',
    'value'	    => set_value('bonus_2', $record->bonus_2),
    'class'         => $field_class,
);

$bonus_3 = array(
    'name'	    => 'bonus_3',
    'id'	    => 'bonus_3',
    'value'	    => set_value('bonus_3', $record->bonus_3),
    'class'         => $field_class,
);

$award_id_winner = array(
    'name'	    => 'award_id_winner',
    'id'	    => 'award_id_winner',
    'value'	    => set_value('award_id_winner', $record->award_id_winner),
    'class'         => $field_class,
);

$award_id_participant = array(
    'name'	    => 'award_id_participant',
    'id'	    => 'award_id_participant',
    'value'	    => set_value('award_id_participant', $record->award_id_participant),
    'class'         => $field_class,
);

$enabled = array(
    'name'	    => 'enabled',
    'id'	    => 'enabled',
    'value'	    => TRUE,
    'checked'	    => $record->enabled ? TRUE : FALSE,
    'class'	    => $field_class,
);

?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">

                    <h2 class="panel-title">Event Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/event_admin/create_event', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
			<label class="col-md-3 control-label">Name <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>

                    <div class="form-group">
			<label class="col-md-3 control-label">Description <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_textarea($description); ?></div>
                    </div>
		    
		    <div class="form-group">				
			<?php echo form_label('Start Time', $time_start['id'], $label_attributes); ?>
			<div class="col-md-6"><input type="text" name="time_start" id="time_start" value="<?php echo date("m/d/Y", strtotime($record->time_start)); ?>" data-plugin-datepicker class="form-control"></div>
		    </div>
                    
		    <div class="form-group">				
			<?php echo form_label('End Time', $time_end['id'], $label_attributes); ?>
			<div class="col-md-6"><input type="text" name="time_end" id="time_end" value="<?php echo date("m/d/Y", strtotime($record->time_end)); ?>" data-plugin-datepicker class="form-control"></div>
		    </div>		    
		    
                    <div class="form-group">
                        <?php echo form_label('Event Type', $event_type_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('event_type_id', $event_types, $event_type_id['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Jumpseat Waiver', $waiver_js['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($waiver_js); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('A/C CAT Waiver', $waiver_cat['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($waiver_cat); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Airline', $airline_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('airline_id', $airlines, $airline_id['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Airport', $airport_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('airport_id', $airports, $airport_id['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Airport', $airport_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div id="airports">
                            <input class="typeahead" name="airport" type="text" placeholder="Search for Airport">
			    </div>
                        </div>
                    </div>		    
		    
		    <div class="form-group">
                        <?php echo form_label('Aircraft Category', $aircraft_cat_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('aircraft_cat_id', $aircraft_cats, $aircraft_cat_id['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Target Landing Rate', $landing_rate['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($landing_rate); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Traget Flight Time', $flight_time['id'], $label_attributes); ?>
			<div class="col-md-6"><input type="text" name="flight_time" id="flight_time" value="<?php echo $record->flight_time; ?>" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }'></div>
		    </div>	

		    <div class="form-group">
                        <?php echo form_label('Minimum Flights', $total_flights['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('total_flights', $zero_to_ten, $total_flights['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('1st Place Bonus Hours', $bonus_1['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('bonus_1', $zero_to_ten, $bonus_1['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('2nd Place Bonus Hours', $bonus_2['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('bonus_2', $zero_to_ten, $bonus_2['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('3rd Place Bonus Hours', $bonus_3['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('bonus_3', $zero_to_ten, $bonus_3['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Winner Award', $award_id_winner['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('award_id_winner', $awards, $award_id_winner['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Participant Award', $award_id_participant['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('award_id_participant', $awards, $award_id_participant['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Enabled', $enabled['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($enabled); ?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <?php echo form_submit('submit', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    </div>
</div>