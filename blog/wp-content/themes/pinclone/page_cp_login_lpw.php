<?php
/*
Template Name: _login_lpw
*/

define("DONOTCACHEPAGE", true);

if (is_user_logged_in()) { wp_redirect(home_url()); exit; }
if (isset($_POST['action']) && !wp_verify_nonce($_POST['nonce'], 'lost-password')) { die(); }

if (of_get_option('captcha_public') != '' && of_get_option('captcha_private') != '')
	require_once(get_template_directory() . '/recaptchalib.php');

if (isset($_POST['action']) && $_POST['action'] == 'pwreset') {
	$reset_status = retrieve_password();	
}

if (isset($_GET['action']) && $_GET['action'] == 'rp' && $_POST['action'] != 'rp') {
	$user = check_password_reset_key($_GET['key'], $_GET['login']);

	if (is_wp_error($user)) {
		$invalid_key = 'invalid';
	}
}

if (isset($_POST['action']) && $_POST['action'] == 'rp') {
	$user = get_user_by('login', $_POST['user_login']);
	$rp_status = '';
	
	if ($_POST['pass1'] != $_POST['pass2']) {
		$rp_status = __('The passwords do not match.', 'pinclone');
	} else if (strlen($_POST['pass1']) < 6) {
		$rp_status = __('Passwords must be at least 6 characters long.', 'pinclone');
	} else {
		reset_password($user, $_POST['pass1']);
		wp_redirect(home_url('/login/?pw=reset'));
		exit;
	}
}

//function from wp-login.php
function retrieve_password() {
	global $wpdb, $wp_hasher;

	$errors = new WP_Error();
	
	//edited: check if captcha is correct
	if (of_get_option('captcha_public') != '' && of_get_option('captcha_private') != '') {
		$privatekey = of_get_option('captcha_private');
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]
				);

		if (!$resp->is_valid) {
			$errors->add('incorrect_captcha', __('<strong>ERROR</strong>: Incorrect Captcha.', 'pinclone'));
		}
	}

	if ( empty( $_POST['user_login'] ) ) {
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.', 'pinclone'));
	} else if ( strpos( $_POST['user_login'], '@' ) ) {
		$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.', 'pinclone'));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('login', $login);
	}

	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 */
	do_action( 'lostpassword_post' );

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', 'pinclone'));
		return $errors;
	}

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	/**
	 * Fires before a new password is retrieved.
	 *
	 * @since 1.5.2
	 * @deprecated 1.5.2 Misspelled. Use 'retrieve_password' hook instead.
	 *
	 * @param string $user_login The user login name.
	 */
	do_action( 'retreive_password', $user_login );
	/**
	 * Fires before a new password is retrieved.
	 *
	 * @since 1.5.2
	 *
	 * @param string $user_login The user login name.
	 */
	do_action( 'retrieve_password', $user_login );

	/**
	 * Filter whether to allow a password to be reset.
	 *
	 * @since 2.7.0
	 *
	 * @param bool true           Whether to allow the password to be reset. Default true.
	 * @param int  $user_data->ID The ID of the user attempting to reset a password.
	 */
	$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

	if ( ! $allow )
		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'pinclone'));
	else if ( is_wp_error($allow) )
		return $allow;

	// Generate something random for a password reset key.
	$key = wp_generate_password( 20, false );

	/**
	 * Fires when a password reset key is generated.
	 *
	 * @since 2.5.0
	 *
	 * @param string $user_login The username for the user.
	 * @param string $key        The generated password reset key.
	 */
	do_action( 'retrieve_password_key', $user_login, $key );

	// Now insert the key, hashed, into the DB.
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}
	$hashed = $wp_hasher->HashPassword( $key );
	$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
	
	$message = __('Someone requested that the password be reset for the following account:', 'pinclone') . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s', 'pinclone'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'pinclone') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:', 'pinclone') . "\r\n\r\n";
	$message .= home_url("/login-lpw/?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n"; //edited: change url

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Password Reset', 'pinclone'), $blogname );

	/**
	 * Filter the subject of the password reset email.
	 *
	 * @since 2.8.0
	 *
	 * @param string $title Default email title.
	 */
	$title = apply_filters( 'retrieve_password_title', $title );
	/**
	 * Filter the message body of the password reset mail.
	 *
	 * @since 2.8.0
	 *
	 * @param string $message Default mail message.
	 * @param string $key     The activation key.
	 */
	$message = apply_filters( 'retrieve_password_message', $message, $key );

	if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
		wp_die( __('The e-mail could not be sent.', 'pinclone') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.', 'pinclone') );

	return true;
}
?>

<?php get_header(); ?>

<div class="container-fluid">
	<div class="row-fluid">

		<div class="span4 hidden-phone"></div>

		<div class="span4 usercp-wrapper">
		<?php if (!isset($_GET['action']) || $invalid_key == 'invalid') { ?>
			<h1><?php _e('Lost Your Password?', 'pinclone') ?></h1>
			
			<?php if (isset($reset_status) && is_wp_error($reset_status)) { ?>
			<div class="error-msg"><div class="alert"><strong><?php echo $reset_status->get_error_message(); ?></strong></div></div>
			<?php } else if (isset($reset_status) && $reset_status != '') { ?>
			<div class="error-msg"><div class="alert alert-success"><strong><?php _e('Check your e-mail for the confirmation link.', 'pinclone'); ?></strong></div></div>
			<?php } else if (isset($invalid_key) && $invalid_key == 'invalid') { ?>
			<div class="error-msg"><div class="alert alert"><strong><?php _e('Sorry, that key does not appear to be valid.', 'pinclone'); ?></strong></div></div>
			<?php } ?>
			
			<form id="resetpwform" action="<?php echo home_url('/login-lpw/'); ?>" method="post">
				<label><?php _e('Username or Email', 'pinclone'); ?><br />
				<input type="text" name="user_login" id="user_login" value="" /></label>

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
				<input type="hidden" name="action" value="pwreset" />
				<input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce('lost-password'); ?>" />
				<input type="submit" class="btn btn-large btn-primary" name="wp-submit" id="wp-submit" value="<?php _e('Get New Password', 'pinclone'); ?>" />

				<br /><br />
				<?php _e('You will receive a link to create a new password via email.', 'pinclone'); ?>
			</form>
		<?php } else if ($_GET['action'] == 'rp') { ?>
			<h1><?php _e('Reset Password', 'pinclone') ?></h1>
			
			<?php if ($rp_status != '') { ?>
			<div class="error-msg"><div class="alert"><strong><?php echo $rp_status; ?></strong></div></div>
			<?php } ?>
			
			<form id="resetpwform" action="<?php echo home_url('/login-lpw/?action=rp'); ?>" method="post">
				<label for="pass1"><?php _e('New password', 'pinclone') ?><br />
				<input type="password" name="pass1" id="pass1" class="input" value="" autocomplete="off" /></label>

				<label for="pass2"><?php _e('Confirm new password', 'pinclone') ?><br />
				<input type="password" name="pass2" id="pass2" class="input" value="" autocomplete="off" /></label>

				<br />
				<input type="hidden" name="user_login" id="user_login" value="<?php echo esc_attr($_GET['login'] ? $_GET['login'] : $_POST['user_login']); ?>" />
				<input type="hidden" name="action" id="action" value="rp" />
				<input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce('lost-password'); ?>" />
				<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-large btn-primary" value="<?php esc_attr_e('Reset Password', 'pinclone'); ?>" tabindex="100" />
			</form>
		<?php } ?>
		</div>

		<div class="span4"></div>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	$('#user_login').focus();
});
</script>

<?php get_footer(); ?>