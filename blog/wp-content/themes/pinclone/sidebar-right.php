<div id="sidebar-right" class="sidebar<?php if (is_single() && !in_category(pinclone_blog_cats())) { echo ' sidebar-right-single'; } ?>">
<?php if (!dynamic_sidebar('sidebar-right')) : ?>
<?php endif ?>
</div>