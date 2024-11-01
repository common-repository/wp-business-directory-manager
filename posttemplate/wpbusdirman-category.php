<?php
	get_header();
?>
<div class="wpbdmentry">
<?php
	print(wpbusdirman_post_catpage_title());
	print(wpbusdirman_post_menu_buttons());
	wpbusdirman_catpage_query();
	if ( have_posts() )
	{
		while ( have_posts() )
		{
			the_post();
			print(wpbusdirman_post_excerpt());
		}
		wp_reset_query();
?>
	<div class="navigation">
<?php
		if(function_exists('wp_pagenavi'))
		{
			wp_pagenavi();
		}
		else
		{
?>
		<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
<?php
		}
?>
	</div>
<?php
	}
	else
	{
		_e("No listings found in category","WPBDM");
	}
?>
</div>
<?php
	get_footer();
