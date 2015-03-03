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

$event_type_id = array(
    'name'	    => 'event_type_id',
    'id'	    => 'event_type_id',
    'value'	    => set_value('event_type_id', $record->event_type_id),
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
                        <?php echo form_label('Event Type', $event_type_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('event_type_id', $event_types, $event_type_id['value'], "class='{$field_class}'"); ?>
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