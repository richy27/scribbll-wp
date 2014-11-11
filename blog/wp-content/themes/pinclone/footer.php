<div class="clearfix"></div>

<div id="popup-login-overlay"></div>

<?php if(!is_user_logged_in()) { ?>
<div class="modal hide" id="popup-login-box" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-header">
		<button id="popup-login-close" type="button" class="close" aria-hidden="true">x</button>
		<div><?php _e('Welcome', 'pinclone'); ?></div>
	</div>
	
	<div class="modal-footer">
		<?php if (function_exists('wsl_activate')) { do_action('wordpress_social_login'); echo '<hr />'; } ?>
		<small><div class="error-msg-loginbox"></div></small>
		<form name="loginform_header" id="loginform_header" method="post">
			<input type="text" name="log" id="log" value="" tabindex="0" /><span class="help-inline"> <?php _e('Username or Email', 'pinclone'); ?></span>
			<br />
			<input type="password" name="pwd" id="pwd" value="" tabindex="0" /><span class="help-inline"> <?php _e('Password', 'pinclone'); ?> (<a href="<?php echo home_url('/login-lpw/'); ?>" tabindex="-1"><?php _e('Forgot', 'pinclone'); ?>?</a>)</span>
			<br />
			<input type="submit" class="pull-left btn btn-primary" name="wp-submit" id="wp-submit" value="<?php _e('Login', 'pinclone'); ?>" tabindex="0" />
			<div class="ajax-loader-loginbox pull-left ajax-loader hide"></div>
			<span id="popup-box-register" class="pull-left"><?php _e('or', 'pinclone'); ?> <a href="<?php echo home_url('/register/'); ?>" tabindex="0">Register Account</a></span>
		</form>
		<br />
	</div>
</div>
<?php } ?>

<div id="scrolltotop"><a href="#"><i class="fa fa-chevron-up"></i><br /><?php _e('Top', 'pinclone'); ?></a></div>

<noscript>
	<div id="noscriptalert"><?php _e('You need to enable Javascript.', 'pinclone'); ?></div>
</noscript>

<?php wp_footer(); ?>
<?php echo of_get_option('footer_scripts'); ?>
</body>
</html>