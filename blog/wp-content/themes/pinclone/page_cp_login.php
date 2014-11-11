<?php
/*
Template Name: _login
*/

define("DONOTCACHEPAGE", true);

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
	if (wp_verify_nonce($_GET['nonce'], 'logout')) {
		wp_logout();
		wp_safe_redirect(home_url('/login/?action=loggedout'));
		exit();
	}
}

if (is_user_logged_in()) { wp_redirect(home_url()); exit; }

if (of_get_option('captcha_public') != '' && of_get_option('captcha_private') != '')
	require_once(get_template_directory() . '/recaptchalib.php');

get_header();
?>
<div class="container-fluid">
	<div class="row-fluid">

		<div class="span4 hidden-phone"></div>

		<div class="span4 usercp-wrapper hide">
			<h1><?php _e('Login', 'pinclone') ?></h1>
			
			<?php if (isset($_GET['action']) && $_GET['action'] == 'loggedout' && !$_GET['login']) { ?>
				<div class="error-msg-incorrect"><div class="alert alert-success"><strong><?php _e('Logged Out Successfully', 'pinclone'); ?></strong></div></div>
			<?php } ?>
			
			<?php 
			if (function_exists('wsl_activate')) {
				do_action('wordpress_social_login');
			}
			?>

			<?php if (isset($_GET['pw']) && $_GET['pw'] == 'reset') {   ?>
				<div class="error-msg-incorrect"><div class="alert alert-success"><strong><?php _e('Your password has been reset.', 'pinclone'); ?></strong></div></div>
			<?php } else if (isset($_GET['registration']) && $_GET['registration'] == 'disabled') {   ?>
				<div class="error-msg-incorrect"><div class="alert"><strong><?php _e('User registration is currently closed.', 'pinclone'); ?></strong></div></div>
			<?php } else if (isset($_GET['registration']) && $_GET['registration'] == 'done' ) {   ?>
				<div class="error-msg-incorrect"><div class="alert alert-success"><strong><?php _e('To activate account, please check your email for verification link.', 'pinclone'); ?></strong></div></div>
			<?php } else if (isset($_GET['email']) && $_GET['email'] == 'unverified' ) {   ?>
				<div class="error-msg-incorrect"><div class="alert"><strong><?php _e('Account not activated yet. Please check your email for verification link.', 'pinclone'); ?></strong></div></div>
			<?php } else if (isset($_GET['email']) && $_GET['email'] == 'verify') {
				$user = get_user_by('login', $_GET['login']);
				$key = get_user_meta($user->ID, '_Verify Email', true);
				if ($key == $_GET['key']) {
					delete_user_meta($user->ID, '_Verify Email', $key);
				?>
				<div class="error-msg-incorrect"><div class="alert alert-success"><strong><?php _e('Verification success. You may login now.', 'pinclone'); ?></strong></div></div>
				<?php } else { ?>
				<div class="error-msg-incorrect"><div class="alert"><strong><?php _e('Invalid verification key', 'pinclone'); ?></strong></div></div>
			<?php }
			} else if (isset($_GET['login']) && $_GET['login'] == 'failed') { ?>
				<div class="error-msg-incorrect"><div class="alert"><strong><?php _e('Incorrect Username/Password', 'pinclone'); if (of_get_option('captcha_public') != '' && of_get_option('captcha_private') != '') { _e('/Captcha', 'pinclone'); } ?></strong></div></div>
			<?php } ?>

			<div class="error-msg-blank"></div>
			
			<form name="loginform" id="loginform" action="<?php echo site_url('wp-login.php', 'login_post'); ?>" method="post">
				<label><?php _e('Username or Email', 'pinclone'); ?><br />
				<input type="text" name="log" id="log" value="" tabindex="10" /></label>

				<label><?php _e('Password', 'pinclone'); ?><br />
				<input type="password" name="pwd" id="pwd" value="" tabindex="20" /></label>
				
				<?php
				if (of_get_option('captcha_public') != '' && of_get_option('captcha_private') != '') {
					$publickey = of_get_option('captcha_public');
					echo recaptcha_get_html($publickey);
				?>
				<div id="recaptcha_div"></div>
				<script type='text/javascript'>
					Recaptcha.create("<?php echo $publickey; ?>",
						"recaptcha_div", {
							theme: "white",
							callback: Recaptcha.focus_response_field
				});
				</script> 
				<?php }	?>

				<br />
				<input type="hidden" name="rememberme" id="rememberme" value="forever" />
				<input type="hidden" name="redirect_to" id="redirect_to" value="<?php if ($_GET['redirect_to']) { echo esc_attr($_GET['redirect_to']); } else { echo esc_attr(home_url('/')); } ?>" />
				<input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce('login'); ?>" />
				<input type="hidden" name="formname" id="formname" value="pinclone_loginform" />
				<input type="submit" class="btn btn-large btn-primary" name="wp-submit" id="wp-submit" value="<?php _e('Login', 'pinclone'); ?>" tabindex="30" />

				<br /><br />
				<p class="moreoptions">
				<a href="<?php echo home_url('/register/'); ?>"><?php _e('Register account', 'pinclone'); ?></a> | 
				<a href="<?php echo home_url('/login-lpw/'); ?>"><?php _e('Lost your password?', 'pinclone'); ?></a>
				</p>
			</form>
		</div>

		<div class="span4"></div>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	$('.usercp-wrapper').show();
	$('#log').focus();
});
</script>

<?php get_footer(); ?>