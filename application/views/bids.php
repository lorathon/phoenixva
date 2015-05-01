
<div class="container">
    <div class="row">
	<div class="col-md-12">
	    <div class="panel-body">
		<div class="col-md-6">
		    <div class="dd" id="nestable">
			<ol class="dd-list">
			    <?php foreach($bids as $bid) : ?>
			    <li class="dd-item" data-id="<?php echo $bid->id; ?>">
				<div class="dd-handle"><?php echo 'ID - ' . $bid->id . '  |  Sort - ' . $bid->sort . '  |  Ver - ' . $bid->version; ?></div>
			    </li>
			    <?php endforeach; ?>
			</ol>
		    </div>
		</div>
		<div class="col-md-6">
		    <textarea id="nestable-output" rows="3" class="form-control"></textarea>
		</div>
	    </div>
	</div>
    </div>    
</div>

