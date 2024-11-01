<div class="wpbdmentry">
<?php
	print(wpbusdirman_post_menu_buttons());
	wpbusdirman_indexpage_query();
	if ( have_posts() )
	{
		while ( have_posts() )
		{
			the_post();
			print(wpbusdirman_post_excerpt());
		}
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
?>
	<p><?php _e("There were no listings found in the directory","WPBDM"); ?></p>
<?php
	}
	wp_reset_query();
?>
</div>
<?php
