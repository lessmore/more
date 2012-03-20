			<div id="post-<?php the_ID(); ?>" class="post-home">
				<div class="post-thumbnail">
					<?php if(post_thumbnail(0)>0) : ?>
						<?php echo '<a href="';echo the_permalink();echo'">';echo post_thumbnail(1);echo '</a>';?>
					<?php else : ?>
						<?php echo '<p>';echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 500,"..."); echo '</p>';?>
					<?php endif; ?>
					<span class="bg"></span>
					<span class="text">
						<?php if(function_exists('the_views')) {echo '<span class="post-view">';echo the_views();echo '</span>';} ?>
						<span class="post-count"><?php echo post_thumbnail(0);?><?php _e( ' Photos','iphoto');?></span>
					</span>
				</div><!--end .post-thumbnail -->
				<div class="post-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					<span class="post-date"><?php the_time('Y.m.d G:i'); ?></span>
				</div><!--end .post-title -->
			</div><!--end .post-home -->