<?php
$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role'  => 'form',
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
);

$description = array(
    'name'	    => 'description',
    'id'	    => 'description',
    'value'	    => set_value('description', $record->description),
    'class'         => $field_class,
    'rows'          => 4,
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
    'value'	    => set_value('waiver_js', $record->event_type_id),
    'checked'	    => FALSE,
    'class'	    =>$field_class,
);

$waiver_cat = array(
    'name'	    => 'waiver_cat',
    'id'	    => 'waiver_cat',
    'value'	    => set_value('waiver_cat', $record->event_type_id),
    'checked'	    => FALSE,
    'class'	    =>$field_class,
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

?>

<div class="container">

    <?php if ($errors): ?>
        <div class="alert alert-danger">            
            <p><?php echo $errors; ?></p>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-4">
            <section class="panel">
                <header class="panel-heading">

                    <h2 class="panel-title">Event Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/events/create_event', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
                        <?php echo form_label('Name', $name['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Description', $description['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_textarea($description); ?></div>
                    </div>
		    
		    <div class="form-group">				
			<?php echo form_label('Start Time', $time_start['id'], $label_attributes); ?>
			<div class="col-md-6"><input type="text" name="time_start" id="time_start" value="<?php echo $record->time_start; ?>" data-plugin-datepicker class="form-control"></div>
		    </div>
                    
		    <div class="form-group">				
			<?php echo form_label('End Time', $time_end['id'], $label_attributes); ?>
			<div class="col-md-6"><input type="text" name="time_end" id="time_end" value="<?php echo $record->time_end; ?>" data-plugin-datepicker class="form-control"></div>
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
                        <div class="col-md-12">
                            <?php echo form_submit('save', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    </div>
</div>