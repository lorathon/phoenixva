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
					<th>TR ID</th>
    					<th>CAT</th>
				    </tr>
				</thead>
				<tbody>
				    <?php if ($airframes) : ?>
					<?php foreach ($airframes as $row): ?>
					    <tr>
						<td><?php echo $row->iata; ?></td>
						<td><?php echo $row->icao; ?></td>
						<td><?php echo $row->name; ?></td>
						<td><?php echo $row->aircraft_sub_id; ?></td>
	    					<td><?php echo $cat[$row->category]; ?></td>
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