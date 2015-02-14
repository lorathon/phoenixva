<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p class="lead">
				Phoenix Virtual Airways operates from <?php echo count($hubs); ?>
				crew centers located throughout the world. Each pilot selects a
				crew center when joining and has opportunities to transfer to
				other crew centers throughout their career. With Phoenix Virtual
				Airways, you never have to visit your crew center. Crew centers
				provide a means of grouping our pilots into teams for various
				contests and events. 
			</p>
			<p>Click on a crew center to learn more about it.</p>
			<?php foreach ($hubs as $hub): ?>
				<p><?php echo $hub->name; ?></p>
			<?php endforeach; ?>
		</div>
	</div>
</div>