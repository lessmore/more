<?php get_header(); ?>
	<div id="container">
		<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; endif; ?>
	</div><!--end container-->
	<div id="pagenavi">
		<?php pagenavi();?>
	</div><!--end pagenavi-->
	<div class="clear"></div>
<?php get_footer(); ?>