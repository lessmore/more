<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			
			<div class="navigation">			
				<div class="prev"><?php previous_post_link('%link', esc_html__('Previous', 'gpp'), TRUE); ?></div>
				<div class="next"><?php next_post_link('%link', esc_html__('Next', 'gpp'), TRUE); ?></div>
			</div>
			
			<h2 class="posttitle"><?php the_title(); ?></h2>
			<span class="posted"><?php esc_html_e('Posted on', 'gpp'); ?> <?php the_time('l, F jS, Y') ?> <?php esc_html_e('at', 'gpp'); ?> <?php the_time() ?></span>
			<div class="entry">
				<?php the_content('<p class="serif">'. esc_html__('Read the rest of this entry &raquo;', 'gpp') . '</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>'. esc_html__('Pages:','gpp') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( __('<p class="tags">Tags: ', 'gpp'), ', ', '</p>'); ?>

				<p class="postmetadata alt">
					Filed under <?php the_category(', ') ?>.
						<?php edit_post_link(esc_html__('Edit this entry.', 'gpp'),'','.'); ?> 
				</p>

			</div>
		</div>
		

		<div class="clear"></div>
		

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php esc_html_e('Sorry, no attachments matched your criteria.', 'gpp'); ?></p>

<?php endif; ?>

	</div>
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>
