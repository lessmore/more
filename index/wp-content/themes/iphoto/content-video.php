			<div id="post-<?php the_ID(); ?>" class="post-home video">
				<div class="post-thumbnail">
					<?php the_content(); ?>
				</div><!--end post_thumbnail -->
					<div class="post-info">
						<span class="post-date"><?php the_time('Y.m.d'); ?></span><span class="post-commas">,</span>
						<span class="post-comment"><?php comments_popup_link(__('0 Comments', 'iphoto'), __('1 Comment', 'iphoto'), __('% Comments', 'iphoto')); ?></span>
					</div><!--end post-info -->
			</div><!--end index-post -->