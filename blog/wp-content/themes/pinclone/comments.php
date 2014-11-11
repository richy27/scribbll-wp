<?php
	if (post_password_required())
		return;
?>

<div id="comments">
	<?php if (have_comments()) : ?>

		<ol class="commentlist">
			<?php wp_list_comments(array('callback' => 'pinclone_list_comments')); ?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
		<ul class="pager">
			<li class="previous"><?php previous_comments_link(__( '&laquo; Older Comments', 'pinclone')); ?></li>
			<li class="next"><?php next_comments_link(__('Newer Comments &raquo;', 'pinclone')); ?></li>
		</ul>
		<?php endif;?>

	<?php
	elseif (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
	endif;
	
	if (is_user_logged_in()) {
		global $user_ID;

		comment_form(array(
		'title_reply' => '',
		'title_reply_to' => '',
		'cancel_reply_link' => __('X Cancel reply', 'pinclone'),
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'logged_in_as' => '',
		'label_submit' => __('Post Comment', 'pinclone'),
		'comment_field' => '<div class="pull-left">' . get_avatar($user_ID, '48') . '</div>' . '<div class="textarea-wrapper"><textarea placeholder="' . __('Add a comment...', 'pinclone') . '" id="comment" name="comment" aria-required="true"></textarea></div>'
		));
	} else if (comments_open()) {
	?>
		<form method="post" id="commentform">
			<div class="pull-left"><?php echo get_avatar('', '48'); ?></div>
			<div class="textarea-wrapper"><textarea disabled placeholder="<?php _e('Login to comment...', 'pinclone'); ?>"></textarea><a class="btn" href="<?php echo wp_login_url(get_permalink()); ?>"><strong><?php _e('Login', 'pinclone'); ?></strong></a></div>
		</form>
	<?php
	}
	?>
</div>