<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php if (! is_null($pubdate)): ?>
				<p class="text-right"><small>Published <?php echo $pubdate; ?></small></p>
			<?php endif; ?>
			<?php echo $body; ?>
		</div>
	</div>
</div>