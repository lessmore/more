			<!-- begin sidebar -->
			<div id="sidebar">
		
			
<?php if(get_option('cici_ads')=='yes'){?>
<div class="sidebar-widg">
<h2>Advertisement</h2>
<div class="clear"></div>
<?php include (TEMPLATEPATH . '/ad1.php'); ?>
</div>
<?php }?>

	
	<?php if(get_option('cici_videos')=='yes'){?>
	<div class="sidebar-widg">
<?php include (TEMPLATEPATH . '/video.php'); ?></div>
<?php }?>
	<div class="sidebar-widg">
	<div class="clear"></div>
<h2>Archives</h2>
<div class="clear"></div>
<ul>
 <?php wp_get_archives('show_post_count=1&title_li='); ?> 
</ul>
</div>
<div class="sidebar-widg">
<h2>Flickr Photos</h2>
<div class="clear"></div>
<div id="flickr">
<?php if (function_exists('get_flickrRSS')) get_flickrRSS(); ?>
</div>
</div>

<?php 
	/* Widgetized sidebar */
	if ( ! dynamic_sidebar( 'Right Sidebar' ) ) :
			  endif; ?>



			</div>
			<!-- end sidebar -->
		<!-- end colRight -->
