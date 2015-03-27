<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title></title></head>
<body style="color: #777777; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; line-height: 22px;">
	<div style="max-width: 800px; margin: 0;">
		<div style="padding-right: 15px; padding-left: 15px;">
			<table width="100%">
				<tr>
					<td style="text-align: left;">
						<h1 class="logo">
							<?php echo anchor('','<img alt="Phoenix Virtual Airways" width="180" height="60" src="'.base_url('assets/img/Logo.png').'">','title="Home Page"'); ?>
						</h1>
					</td>
					<td style="text-align: right;">
						<?php echo anchor('auth/login','SIGN IN', 'style="font-size: 12px;
  font-style: normal;
  line-height: 20px;
  margin-left: 3px;
  margin-right: 3px;
  text-transform: uppercase;
  font-weight: 700;
  padding: 10px 13px;"'); ?>
						<?php echo anchor('http://helpdesk.phoenixva.org/','HELP DESK', 'style="font-size: 12px;
  font-style: normal;
  line-height: 20px;
  margin-left: 3px;
  margin-right: 3px;
  text-transform: uppercase;
  font-weight: 700;
  padding: 10px 13px;"'); ?>
					</td>
				</tr>
			</table>
		</div>
		<div style="background-color: #171717;
  				border-bottom: 5px solid #CCC;
  				border-top: 5px solid #384045;
  				margin-bottom: 35px;
  				min-height: 50px;
  				padding: 20px 0;
  				padding-right: 15px;
  				padding-left: 15px;
  				position: relative;
  				text-align: left;">
  			<?php echo anchor('', 'Home', 'style="color: #0088cc;"'); ?><br />
  			<h1 style="border-bottom: 5px solid #0088cc;
  					color: #FFF;
  					display: inline-block;
  					font-weight: 200;
  					margin: 0 0 -25px;
  					min-height: 37px;
  					font-size: 2.6em;
  					line-height: 46px;
  					padding: 0 0 17px;
  					position: relative;"><?php echo $subject; ?></h1>
  		</div>
		<div style="padding-right: 15px; padding-left: 15px;">
			<?php echo $view_output_html; ?>
		</div>
		<div style="background: #0e0e0e;
  				border-top: 4px solid #0e0e0e;
  				font-size: 0.9em;
  				margin-top: 50px;
  				padding: 70px 0 0;
  				padding-right: 15px;
  				padding-left: 15px;
  				position: relative;
  				clear: both;">
  			<h4 style="font-size: 1.8em;
  					font-weight: 200;
  					color: #FFF !important;
  					letter-spacing: normal;
  					line-height: 27px;
  					margin: 0 0 14px 0;">
  				About Phoenix Virtual Airways
  			</h4>
  			<p style="color: #777777;
  					line-height: 24px;
  					margin: 0 0 20px;">
  				Phoenix Virtual Airways brings forth experienced virtual airline 
  				managers and a core group of pilots who seek a rewarding, fun 
  				flying experience as part of a community of flight simulation 
  				enthusiasts.
  			</p>
  			<p style="color: #777777;
  					line-height: 24px;
  					margin: 0 0 20px;">
  				You are receiving this email because you have an account at
  				Phoenix Virtual Airways. If you no longer wish to receive email
  				from us, please tell us via email to 
  				<a href="mailto:helpdesk@phoenixva.org" 
  				   style="color: #FFF !important;">helpdesk@phoenixva.org</a> 
  				and we will remove your account.
  			</p>
  			<hr />
  			<p style="color: #777777;
  					line-height: 24px;
  					margin: 0 0 20px;
  					text-align: center">
  				© Copyright 2015. All Rights Reserved. For flight simulation 
  				purposes only. We are in no way affiliated with any real-world 
  				entities.
  			</p>
		</div>
	</div>
</body>
</html>