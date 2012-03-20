<?php get_header(); ?>

	<div id="content">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2 class="entry-title"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h2>
			<div class="entry">
				<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
				<div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></div>

				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<div class="navigation">
					<div class="alignleft"><?php previous_image_link() ?></div>
					<div class="alignright"><?php next_image_link() ?></div>
				</div>
				<br class="clear" />

				<p class="postmetadata alt">
					<small>
					    <?php esc_html_e('This entry was posted on', 'gpp'); ?> <?php the_time('l, F jS, Y') ?> <?php esc_html_e('at', 'gpp'); ?> <?php the_time() ?>
						<?php esc_html_e('and is filed under', 'gpp'); ?> <?php the_category(', ') ?>.
						<?php the_taxonomies(); ?>
						<?php esc_html_e('You can follow any responses to this entry through the', 'gpp'); ?> <?php post_comments_feed_link('RSS 2.0'); ?> <?php esc_html_e('feed', 'gpp'); ?>.

						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							<?php esc_html_e('You can', 'gpp'); ?> <a href="#respond"><?php esc_html_e('leave a response', 'gpp'); ?></a>, <?php esc_html_e('or', 'gpp'); ?> <a href="<?php trackback_url(); ?>" rel="trackback"><?php esc_html_e('trackback', 'gpp'); ?></a> <?php esc_html_e('from your own site', 'gpp'); ?>.

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							<?php esc_html_e('Responses are currently closed, but you can', 'gpp'); ?> <a href="<?php trackback_url(); ?> " rel="trackback"><?php esc_html_e('trackback', 'gpp'); ?></a> <?php esc_html_e('from your own site', 'gpp'); ?>.

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							<?php esc_html_e('You can skip to the end and leave a response. Pinging is currently not allowed.', 'gpp'); ?>

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							<?php esc_html_e('Both comments and pings are currently closed.', 'gpp'); ?>

						<?php } edit_post_link(esc_html__('Edit this entry.', 'gpp'),'',''); ?>

					</small>
				</p>

			</div>

		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php esc_html_e('Sorry, no attachments matched your criteria.', 'gpp'); ?></p>

<?php endif; ?>

	</div>

<?php get_footer(); ?>
