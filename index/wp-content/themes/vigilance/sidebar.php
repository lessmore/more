<?php global $vigilance; ?>
<div id="sidebar">
	<ul>
		<?php if ( ! dynamic_sidebar( 'wide_sidebar' ) ) : ?>
			<li class="widget widget_recent_entries">
				<h2 class="widgettitle"><?php _e( 'Recent Articles', 'vigilance' ); ?></h2>
				<?php $side_posts = new WP_Query( 'numberposts=10' ); ?>
				<?php if ( $side_posts->have_posts() ) : ?>
					<ul>
						<?php while( $side_posts->have_posts() ) : $side_posts->the_post(); ?>
							<li><a href= "<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endif; ?>
	</ul>
</div><!--end sidebar-->