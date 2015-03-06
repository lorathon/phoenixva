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

$color_id = array(
    'name'	    => 'color_id',
    'id'	    => 'color_id',
    'value'	    => set_value('color_id', $record->color_id),
    'class'         => $field_class,
);

?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Event Type Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/events/create_event_type', $form_attributes); ?>

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
                        <?php echo form_label('Calendar Color', 'color_id', $label_attributes); ?>
                        <div class="col-md-6">
                            <?php 
                            foreach($calendar_colors as $key => $value) 
                            { 
                                echo '<div class="radio-custom radio-' . $value . '">';
                                $data = array(
                                    'name'        => 'color_id',
                                    'id'          => 'color_id',
                                    'value'       => $key,
                                    'checked'     => ($key == $record->color_id) ? TRUE : FALSE,
                                    );
                                echo form_radio($data);
                                echo '<label for="color_id" class="text-' . $value .'">' . ucfirst($value) . '</label></div>';
                            }
                            ?>
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
