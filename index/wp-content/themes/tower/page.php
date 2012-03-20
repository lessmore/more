<?php get_header(); ?>

	<!-- begin content -->
	<div id="content" class="clearfix">
<?php if(is_home()){?>
		<span>Recent posts</span>
	<?php
	} elseif (function_exists('dimox_breadcrumbs')) {dimox_breadcrumbs();} ?>
<!-- begin contentLeft -->
	<div id="contentLeft">
		<div class="postBox">
		<h1><?php the_title(); ?></h1>
		<div class="clear"></div>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<?php the_content(__('(more...)')); ?>
		
		<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif; ?></div>
	</div>
	<!-- end contentLeft -->
	
	<!-- begin sidebarBox -->
	<div id="sidebarBox">
	<?php get_sidebar(); ?>	 
	</div>
	<!-- end sidebarBox -->
<?php get_footer(); ?>