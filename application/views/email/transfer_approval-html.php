<p>
	Hello <?php echo $user->name; ?>,
</p>
<p>
	Welcome to the <?php echo $hub->get_full_name(); ?> crew center!
	Your request to transfer to <?php echo $hub->get_full_name(); ?> has been
	approved by <?php echo $userdata['rank_short'] . ' ' . $userdata['name'] ?>.
</p>
<p>
	Please contact your new <?php echo $hub->get_full_name(); ?> crew center 
	manager	if you have any questions.
</p>
<p>
	Thank you!<br />
	Phoenix Virtual Airways
</p>