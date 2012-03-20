<?php get_header(); ?>
	<div id="post-single">
		<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post-content">
				<?php the_content(); ?>
			</div><!--end post-content -->
		<?php endwhile; endif; ?>
	</div><!--end single-->
	<div id="single">
		<div id="comments">
			<?php comments_template('', true); ?>
		</div><!--end comments-->
	</div>
<?php get_footer(); ?>