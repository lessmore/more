<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>

		<h2><?php esc_html_e('Search Results', 'gpp'); ?></h2>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(esc_html__('Next &rarr;', 'gpp')) ?></div>
			<div class="alignright"><?php previous_posts_link(esc_html__('&larr; Previous', 'gpp')) ?></div>
		</div>


		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?>>
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<span class="posted"><?php esc_html_e('Posted on', 'gpp'); ?> <?php the_time('l, F jS, Y') ?> <?php esc_html_e('at', 'gpp'); ?> <?php the_time() ?></span>

				<p class="postmetadata alt">
					<?php esc_html_e('Filed under', 'gpp'); ?> <?php the_category(', ') ?>.
						<?php edit_post_link(esc_html__('Edit this entry.', 'gpp'), '<p>', '</p>'); ?>
				</p>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(esc_html__('Next &rarr;', 'gpp')) ?></div>
			<div class="alignright"><?php previous_posts_link(esc_html__('&larr; Previous', 'gpp')) ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php esc_html_e('No posts found. Try a different search?', 'gpp'); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>