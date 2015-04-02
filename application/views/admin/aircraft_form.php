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
    'placeholder'   => 'Name of aircraft',
    'required'      => 'required',    
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

$max_cargo = array(
    'name'	    => 'max_cargo',
    'id'	    => 'max_cargo',
    'value'	    => set_value('max_cargo', $record->max_cargo),
    'class'         => $field_class,
);

$oew = array(
    'name'	    => 'oew',
    'id'	    => 'oew',
    'value'	    => set_value('oew', $record->oew),
    'class'         => $field_class,
);

$mzfw = array(
    'name'	    => 'mzfw',
    'id'	    => 'mzfw',
    'value'	    => set_value('mzfw', $record->mzfw),
    'class'         => $field_class,
);

$mlw = array(
    'name'	    => 'mlw',
    'id'	    => 'mlw',
    'value'	    => set_value('mlw', $record->mlw),
    'class'         => $field_class,
);

$mtow = array(
    'name'	    => 'mtow',
    'id'	    => 'mtow',
    'value'	    => set_value('mtow', $record->mtow),
    'class'         => $field_class,
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Aircraft Form - <?php echo $record->equip; ?></h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('private/fleet/edit-aircraft', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Name <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>
		    
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
			<?php echo form_label('Economy Class Seats', $pax_economy['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_economy); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Cargo Capacity', $max_cargo['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($max_cargo); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('OEW', $oew['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($oew); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('MZFW', $mzfw['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($mzfw); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('MLW', $mlw['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($mlw); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('MTOW', $mtow['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($mtow); ?>
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
