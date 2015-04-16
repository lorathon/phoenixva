<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>

<div class="container">
    <div class="row">
	<ul class="nav nav-tabs">		
	    <li role="presentation" 
	    <?php if (uri_string() == 'airlines/main'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airlines/main', 'Main Carriers'); ?> 
	    </li>
	    <li role="presentation" 
	    <?php if (uri_string() == 'airlines/regional'): ?>
		class="active"
		<?php endif; ?> >
		    <?php echo anchor('/airlines/regional', 'Regional Carriers'); ?> 
	    </li>
	</ul>  
    </div>
    <div class="row">
	<div class=col-md-12">
	    <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>ICAO</th>
                                <th>IATA</th>
                                <th>Name</th>
                                <th>Logo</th>
				<?php if($show_admin) : ?>
                                <th>Actions</th>
				<?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
			    <?php if($airlines) : ?>
                            <?php foreach ($airlines as $row): ?>
                                <tr>
				    <td><?php echo $row->icao; ?></td>
                                    <td><?php echo $row->iata ?></td>
                                    <td><?php echo airline($row); ?></td>   
				    <td>IMAGE</td>
				    <?php if($show_admin) : ?>
                                    <td align="center">					
					<?php echo anchor('private/airlines/edit-airline/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>					
                                    </td>
				    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
			    <?php else : ?>
				<tr>
				    <td colspan="5">There are no airlines of this airline type.</td>
				</tr>
			    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
	</div>
    </div>
</div>