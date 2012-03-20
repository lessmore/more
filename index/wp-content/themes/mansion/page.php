<?php get_header(); ?>

	<div id="content">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2 class="entry-title"><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content('<p class="serif">'. esc_html__('Read the rest of this page &raquo;', 'gpp') . '</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>'. esc_html__('Pages:','gpp') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link(esc_html__('Edit this entry.', 'gpp'), '<p>', '</p>'); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>