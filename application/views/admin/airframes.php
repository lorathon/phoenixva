<?php $this->load->helper('html'); ?>

<header class="page-header">
    <h2>Airframes</h2>
</header>

<!-- start: page -->
<div class="row">

    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-primary">
	    <header class="panel-heading">


		<h2 class="panel-title">View All Airframes</h2>
	    </header>
	    <div class="panel-body">
		<div class="row">
		    <div class=col-md-12">
			<div class="table-responsive">
			    <table class="table table-hover mb-none">
				<thead>
				    <tr>
					<th>IATA</th>
					<th>ICAO</th>
					<th>Name</th>
					<th>Seating</th>
					<th>Cargo</th>
					<th>OEW</th>
					<th>MZFW</th>
					<th>MTOW</th>
					<th>MLW</th>
					<th>TR ID</th>
					<th>Count</th>
    					<th>CAT</th>
					<th>OPTIONS</th>
				    </tr>
				</thead>
				<tbody>
				    <?php if ($airframes) : ?>
					<?php foreach ($airframes as $row): ?>
					    <tr>
						<td><?php echo $row->iata; ?></td>
						<td><?php echo $row->icao; ?></td>
						<td><?php echo $row->name; ?></td>
						<td><?php echo "($row->pax_first) ($row->pax_business) ($row->pax_economy)"; ?></td>
						<td><font color="<?php if($row->max_cargo == 0) echo "#ff0000"; ?>"><?php echo $row->max_cargo; ?> lbs</td>
						<td><font color="<?php if($row->oew == 0) echo "#ff0000"; ?>"><?php echo $row->oew; ?> lbs</td>
						<td><font color="<?php if($row->mzfw == 0) echo "#ff0000"; ?>"><?php echo $row->mzfw; ?> lbs</td>
						<td><font color="<?php if($row->mtow == 0) echo "#ff0000"; ?>"><?php echo $row->mtow; ?> lbs</td>
						<td><font color="<?php if($row->mlw == 0) echo "#ff0000"; ?>"><?php echo $row->mlw; ?> lbs</td>
						<td><font color="<?php if($row->aircraft_sub_id == 0) echo "#ff0000"; ?>"><?php echo $row->aircraft_sub_id; ?></td>
						<td><font color="<?php if($row->count == 0) echo "#ff0000"; ?>"><?php echo $row->count; ?></td>
	    					<td><font color="<?php if($row->category == 0) echo "#ff0000"; ?>"><?php echo $cat[$row->category]; ?></td>
						<td><?php echo anchor('admin/airframes/create_airframe/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?></td>
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
        </section>
    </div>

</div>