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

$pax_first = array(
    'name'	    => 'pax_first',
    'id'	    => 'pax_first',
    'value'	    => set_value('pax_first', $record->pax_first),
    'class'         => $field_class,
);

$pax_business = array(
    'name'	    => 'pax_business',
    'id'	    => 'pax_business',
    'value'	    => set_value('pax_business', $record->pax_business),
    'class'         => $field_class,
);

$pax_economy = array(
    'name'	    => 'pax_economy',
    'id'	    => 'pax_economy',
    'value'	    => set_value('pax_economy', $record->pax_economy),
    'class'         => $field_class,
);

$payload = array(
    'name'	    => 'payload',
    'id'	    => 'payload',
    'value'	    => set_value('payload', $record->payload),
    'class'         => $field_class,
);
?>


<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $record->name; ?></h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('private/airlines/edit-aircraft', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>
		    
		    <div class="form-group">
			<?php echo form_label('1st Class Seats', $pax_first['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_first); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Business Class Seats', $pax_business['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_business); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Economy Class Seats', $pax_economy['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_economy); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Payload Capacity', $payload['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($payload); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
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
