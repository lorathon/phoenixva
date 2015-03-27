<p>
Forgot your password, huh? No big deal.<br />
To create a new password, just follow this link:<br />
<b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">Create a new password</a></b>
</p>
<p>
Link doesn't work? Copy the following link to your browser address bar:<br />
<nobr><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr>
</p>
<p>
You received this email, because it was requested by a 
<a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> 
user. This is part of the procedure to create a new password on the system. If 
you DID NOT request a new password then please ignore this email and your 
password will remain the same.
</p>
<p>
Thank you,<br />
The <?php echo $site_name; ?> Team
</p>