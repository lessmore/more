<?php
define( 'XML_FILE', 'http://icold.me/iphoto/notifier.xml' ); 
function update_notifier_menu() {  
	if (function_exists('simplexml_load_string')) {
	    $xml = simplexml_load_file( XML_FILE );
		$new_version = $xml->latest;
		$new_version = str_replace('.','',$new_version);
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
		$theme_version = $theme_data['Version'];
		$theme_version = str_replace('.','',$theme_version);
		if( $new_version > $theme_version && is_admin()) {
			if(function_exists('add_theme_page')) {
				add_theme_page(' Theme Updates',__('Theme Updates',THEME_NAME), 'administrator', 'theme-update-notifier', 'update_notifier');
			}
					$dir = get_bloginfo('template_directory');
		wp_enqueue_script('updatejquery', $dir . '/includes/update.js', false, '1.0.0', false);
		wp_enqueue_style('updatecss', $dir . '/includes/update.css', false, '1.0.0', 'screen');
		}
	}	
}
add_action('admin_menu', 'update_notifier_menu');  
function update_notifier() {
	$xml = simplexml_load_file( XML_FILE );
	$new_version = $xml->latest;
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); 
	$changelog = $xml->changelog;
	?>
	<div class="wrap">
		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo THEME_NAME; ?><?php _e( 'Theme Updates',THEME_NAME);?></h2>
	    <div id="message" class="updated below-h2"><p><strong><?php _e( 'There is a new version of the iphoto theme available.',THEME_NAME);?></strong><?php _e( ' You have version ',THEME_NAME);?><?php echo $theme_data['Version']; ?><?php _e( ' installed. Update to version ',THEME_NAME);?><?php echo $new_version; ?>.</p></div>
		<img class="image-notifier-img" src="<?php echo get_template_directory_uri() . '/screenshot.jpg'; ?>" />
		<div id="instructions">
		    <h3><?php _e( 'Update Download',THEME_NAME);?></h3>
		    <p style="color:#DD4B39"><?php _e( 'Please note: make a backup of the theme bofore update.',THEME_NAME);?></p>
		    <p><a href="<?php echo 'http://iphoto.googlecode.com/files/iphoto'.$new_version.'.zip' ;?>" class="button" target="blank"><?php _e( 'Download iPhoto',THEME_NAME);?><strong><?php echo $new_version;?></strong></a></p>
			<h3 class="title"><?php _e( 'Changelog',THEME_NAME);?></h3>
			<?php echo $changelog; ?>
		</div>
	</div>
<?php } 
?>