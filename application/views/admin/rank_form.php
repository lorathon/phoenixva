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

<div id="modalForm" class="modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Registration Form</h2>
        </header>
        <div class="panel-body">
            <form id="demo-form" class="form-horizontal mb-lg" novalidate="novalidate">
                <div class="form-group mt-lg">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" placeholder="Type your name..." required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" class="form-control" placeholder="Type your email..." required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">URL</label>
                    <div class="col-sm-9">
                        <input type="url" name="url" class="form-control" placeholder="Type an URL..." />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Comment</label>
                    <div class="col-sm-9">
                        <textarea rows="5" class="form-control" placeholder="Type your comment..." required></textarea>
                    </div>
                </div>
            </form>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary modal-confirm">Submit</button>
                    <button class="btn btn-default modal-dismiss">Cancel</button>
                </div>
            </div>
        </footer>
    </section>
</div>

