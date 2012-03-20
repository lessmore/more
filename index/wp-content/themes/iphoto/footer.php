	<div class="clear"></div>
	<div id="footer">
		<p><?php if(stripslashes(get_option('iphoto_copyright'))!=''){echo stripslashes(get_option('iphoto_copyright'));}else{echo 'Copyright &copy; '.date("Y").' '.'<a href="'.home_url( '/' ).'" title="'.esc_attr( get_bloginfo( 'name') ).'">'.esc_attr( get_bloginfo( 'name') ).'</a> All rights reserved';}?></p><p>Powered by <a href="http://wordpress.org/" title="Wordpress">WordPress <?php bloginfo('version');?></a>  |  Written by <a href="http://icold.me/" title="iCold">iCold</a> </p>
	</div><!--end footer-->
</div><!--end wrapper-->
<?php wp_footer(); ?>
<?php if (get_option('iphoto_analytics')!="") {?>
<div id="iphotoAnalytics"><?php echo stripslashes(get_option('iphoto_analytics')); ?></div>
<?php }?>
<?php if (is_singular()){ ?>
<?php if(get_option('iphoto_phzoom')!="") : ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/imgZoom.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/single.js"></script>
<?php endif; ?>
<?php }?>
</body>
</html>
