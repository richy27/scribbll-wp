<?php
if (in_category(pinclone_blog_cats())) {
	get_template_part('category', 'blog');
} else {
	get_template_part('index');
}
?>