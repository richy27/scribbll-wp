<?php
if (in_category(pinclone_blog_cats())) {
	get_template_part('single', 'blog');
} else {
	get_template_part('single', 'pin');
}
?>