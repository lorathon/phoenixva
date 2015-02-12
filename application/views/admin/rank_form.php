<?php
$form_attributes = array(
    'class' => 'form-horizontal',
    'role'  => 'form',
);

$label_attributes = array(
    'class' => 'col-sm-1 control-label',
); 

$field_class = 'form-control';

$id = array(
    'name'	    => 'id',
    'id'	    => 'id',
    'value'	    => set_value($record->id),
    'class'         => $field_class,
);

$rank = array(
    'name'	    => 'rank',
    'id'	    => 'rank',
    'value'	    => set_value('rank', $record->rank),
    'class'         => $field_class,
);

$rank_image = array(
    'name'	    => 'rank_image',
    'id'	    => 'rank_image',
    'value'	    => set_value('rank_image', $record->rank_image),
    'class'         => $field_class,
);

$min_hours = array(
    'name'	    => 'min_hours',
    'id'	    => 'min_hours',
    'value'	    => set_value('min_hours', $record->min_hours),
    'class'         => $field_class,
);

$pay_rate = array(
    'name'	    => 'pay_rate',
    'id'	    => 'pay_rate',
    'value'	    => set_value('pay_rate', $record->pay_rate),
    'class'         => $field_class,
);

$short = array(
    'name'	    => 'short',
    'id'	    => 'short',
    'value'	    => set_value('short', $record->short),
    'class'         => $field_class,
);

?>

<div class="container">
    
    <?php if ($errors):?>
        <div class="alert alert-danger">            
            <p><?php echo $errors; ?></p>
	</div>
    <?php endif; ?>     
    
    <?php echo form_open_multipart('admin/ranks/create_rank', $form_attributes); ?>
    
        <?php echo form_hidden('id', $record->id); ?>
    
        <div class="form-group">
            <?php echo form_label('Rank Name', $rank['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($rank); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Rank Image', $rank_image['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($rank_image); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Min Hours', $min_hours['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($min_hours); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Pay Rate', $pay_rate['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($pay_rate); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Short', $short['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($short); ?></div>
	</div>        
    
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo form_submit('save', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
            </div>
        </div>

    <?php echo form_close(); ?>
</div>

