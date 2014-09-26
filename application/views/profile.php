<div class="container">
	<?php if ($errors):?>
		<div class="alert alert-danger">
			<?php foreach ($errors as $error): ?>
				<p><?php echo $error; ?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	
	<h1>Pilot Profile goodness goes here...</h1>
	<?php if ($own_profile): ?>
		<p class="info">BTW, you're checking out your own profile (and man you look good!)</p>
	<?php endif; ?>
</div>
