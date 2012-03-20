<div id="sidebar">
		<ul>
			<li><b><?php the_title(); ?></b></li>
			<li><span class="date"><?php the_time('M d,   Y'); ?></span><?php if(function_exists('the_views')) {echo '<span class="views">';echo the_views();echo '</span>';} ?></span></li>
			<li><?php _e( 'Cate','iphoto');?>&#58;<?php the_category(', '); ?></li>
			<li><?php _e( 'Tags','iphoto');?>&#58;<?php the_tags('', ', ', ''); ?></li>
		</ul>
		<div class="clear"></div>
		<?php if ( get_option( 'iphoto_related')!='' ) : ?>
			<div class="post-related">
				<?php _e( '<h3>Related Posts:</h3>', 'iphoto'); ?>
					<?php if ( get_option( 'iphoto_excerpt')=='' ){ 
						echo '<ul>';echo iphoto_related_posts(4);
						echo '</ul><div class="clear"></div>'; 
					}else{
						$i=get_option( 'iphoto_excerpt'); 
						echo '<ul>';
						echo iphoto_related_posts($i);
						echo '</ul><div class="clear"></div>'; 
					} 
				?>
			</div><!--end .post-related -->
		<?php endif; ?>
		<?php if (get_option('iphoto_as')!="") {?>
			<div id="sidebarAs">
				<?php echo stripslashes(get_option('iphoto_as')); ?>
			</div>
		<?php }?>
		<?php if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('sidebar1') ) : ?>
		<?php endif; ?>
		<div class="clear"></div>
</div>