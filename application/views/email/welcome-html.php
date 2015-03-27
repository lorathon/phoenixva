<p>
Thanks for joining <?php echo $site_name; ?>. We listed your sign in details 
below, make sure you keep them safe.
</p>
<p>
To log in to <?php echo $site_name; ?>, please follow this link:
<b><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;">Go 
to <?php echo $site_name; ?> now!</a></b>
</p>
<p>
Link doesn't work? Copy the following link to your browser address bar:<br />
<nobr><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;"><?php echo site_url('/auth/login/'); ?></a></nobr>
</p>
<p>
<?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><br /><?php } ?>
Your email address: <?php echo $email; ?><br />
<?php /* Your password: <?php echo $password; ?><br /> */ ?>
</p>
<p>
Have fun!<br />
The <?php echo $site_name; ?> Team
</p>