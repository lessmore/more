 <!-- begin footer -->
 <div class="clear"></div>
	</div></div>
<div id="footer-back">
 <div id="footer">
<?php if ( ! dynamic_sidebar( 'Footer' ) ) :
			  endif; ?>
 <div class="clear"></div>
<div id="footer-copyright-link"><a id="footer-logo" href="<?php bloginfo('url'); ?>/"><?php echo get_option('cici_logo_txt'); ?></a>

<div id="topMenu">
		<?php if ( function_exists( 'wp_nav_menu' ) ){
				wp_nav_menu( array( 'theme_location' => 'footerlinks', 'container_id' => 'footerlinks', 'fallback_cb'=>'footerlinks') );
				}else{
					footerlinks();
				}?>

		</div>
	</div>
	</div>
	
	
	<div class="footer-width"><div style="width:900px;margin:0 auto;"><div class="alignleft"><a href="http://www.thoughtmechanics.com/portfolio-wordpress-theme-black-dark-grey-free/">Download Portfolio Theme Here</a> </div><div class="alignright">Designed By <a href="http://www.thoughtmechanics.com/" target="_blank">Chicago Web Design Company</a> | View more <a href="http://www.thoughtmechanics.com/templates/">Wordpress Themes</a></div></div></div>
	</div>
	

 
	<!-- end content -->
	
<!-- end wrapper -->

	


<!-- end footer -->	
<?php if (get_option('cici_analytics') <> "") { 
		echo stripslashes(stripslashes(get_option('cici_analytics'))); 
	} ?>

	<?php wp_footer();?>
    
</body>
</html>