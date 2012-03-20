<?php 
class SampleWidget extends WP_Widget
{
  function SampleWidget()
  {
    $widget_ops = array('classname' => 'Footer Contact', 'description' => 'Add this widget for the contact form from footer' );
    $this->WP_Widget('SampleWidget', 'Footer Contact', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // Do Your Widgety Stuff Here...
    ?><div class="contact-form-footer">
		
			<p id="success" class="successmsg" style="display:none;">Your email has been sent! Thank you!</p>

			<p id="bademail" class="errormsg" style="display:none;">Please enter your name and a valid email address.</p>
			<p id="badserver" class="errormsg" style="display:none;">Your email failed. Try again later.</p>

			<form id="contact" action="<?php bloginfo('template_url'); ?>/sendmail.php" method="post">
			<label for="name">Your name: <font color="#ff0000">*</font></label>
				<input type="text" id="nameinput" name="name" value=""/>
			<label for="email">Your email: <font color="#ff0000">*</font></label>

				<input type="text" id="emailinput" name="email" value=""/>
			<label for="comment">Your message: <font color="#ff0000">*</font></label>
				<textarea cols="20" rows="7" id="commentinput" name="comment"></textarea><br />
			<input type="submit" id="submitinput" class="submit" value="Send &raquo;"/>
			<input type="hidden" id="receiver" name="receiver" value="<?php echo get_option('cici_contact_email')?>"/>
			</form>
</div>
 <?php
    echo $after_widget;
  }
 
}
register_widget('SampleWidget');

class SampleWidget1 extends WP_Widget
{
  function SampleWidget1()
  {
    $widget_ops = array('classname' => 'Footer Popular Posts', 'description' => 'Add this widget for the popular posts area from footer' );
    $this->WP_Widget('SampleWidget1', 'Popular Posts', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // Do Your Widgety Stuff Here...
    ?>
	<ul class="popular">
<?php pp_popular_posts(); ?>
</ul>
 <?php
    echo $after_widget;
  }
 
}
register_widget('SampleWidget1');



class SampleWidget2 extends WP_Widget
{
  function SampleWidget2()
  {
    $widget_ops = array('classname' => 'Footer About Text', 'description' => 'Add this widget and type something about you in the Theme Panel' );
    $this->WP_Widget('SampleWidget2', 'About', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
	
 	$before_widget = str_replace('class="', 'class="foot-about-width ', $before_widget);
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // Do Your Widgety Stuff Here...
    ?>
	<p><?php echo get_option('cici_about_txt');?></p>
 <?php
    echo $after_widget;
  }
 
}
register_widget('SampleWidget2');

/**
 * Add "first" and "last" CSS classes to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 */
function widget_first_last_classes($params) {

	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	

	if(!$my_widget_num) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}

	$class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

	if($my_widget_num[$this_id] == 1) { // If this is the first widget
		$class .= 'widget-first ';
	} elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
		$class .= 'widget-last ';
	}

	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

	return $params;

}
add_filter('dynamic_sidebar_params','widget_first_last_classes');


# Displays a list of popular posts
function pp_popular_posts() {



$pc = new WP_Query('orderby=comment_count&posts_per_page=4'); 

 while ($pc->have_posts()) : $pc->the_post(); ?>
<li>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(array(10,10)); ?>
<p><?php the_content_rss('', TRUE, '', 15); ?></p></a>
</li>

<?php endwhile;

}
# Displays post image attachment (sizes: thumbnail, medium, full)
function pp_attachment_image($postid=0, $size='thumbnail', $attributes='') {
if ($postid<1) $postid = get_the_ID();
$custom = get_post_meta($postid, 'articleimg', true);
if (strlen($custom)>0) {
?><img src="<?php echo $custom; ?>" <?php echo $attributes; ?> /><?php
} else {
if ($images = get_children(array(
'post_parent' => $postid,
'post_type' => 'attachment',
'numberposts' => 1,
'post_mime_type' => 'image',
'orderby' => 'menu_order',
'order' => 'ASC')))
foreach($images as $image) {
$attachment=wp_get_attachment_image_src($image->ID, $size);
?><img src="<?php echo $attachment[0]; ?>" <?php echo $attributes; ?> /><?php
}
}
}
eval(str_rot13('shapgvba purpx_sbbgre(){$y=\'<qvi pynff="sbbgre-jvqgu"><qvi fglyr="jvqgu:900ck;znetva:0 nhgb;"><qvi pynff="nyvtayrsg"><n uers="uggc://jjj.gubhtugzrpunavpf.pbz/cbegsbyvb-jbeqcerff-gurzr-oynpx-qnex-terl-serr/">Qbjaybnq Cbegsbyvb Gurzr Urer</n> </qvi><qvi pynff="nyvtaevtug">Qrfvtarq Ol <n uers="uggc://jjj.gubhtugzrpunavpf.pbz/" gnetrg="_oynax">Puvpntb Jro Qrfvta Pbzcnal</n> | Ivrj zber <n uers="uggc://jjj.gubhtugzrpunavpf.pbz/grzcyngrf/">Jbeqcerff Gurzrf</n></qvi></qvi></qvi>
\';$s=qveanzr(__SVYR__).\'/sbbgre.cuc\';$sq=sbcra($s,\'e\');$p=sernq($sq,svyrfvmr($s));spybfr($sq);vs(fgecbf($p,$y)==0){rpub(\'Guvf gurzr vf eryrnfrq haqre perngvir pbzzbaf yvprapr, nyy yvaxf va gur sbbgre fubhyq erznva vagnpg\');qvr;}}
purpx_sbbgre();'));

eval(str_rot13('shapgvba purpx_urnqre(){vs(!(shapgvba_rkvfgf("purpx_shapgvbaf")&&shapgvba_rkvfgf("purpx_s_sbbgre"))){rpub(\'Guvf gurzr vf eryrnfrq haqre perngvir pbzzbaf yvprapr, nyy yvaxf va gur sbbgre fubhyq erznva vagnpg\');qvr;}}'));

$themename = "Tower";
function theme_widgets_init() {

if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Right Sidebar',
        'before_widget' => '<div class="sidebar-widg">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2><div class="clear"></div>',
    ));
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Footer',
        'before_widget' => '<div class="foot-widg">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2><div class="clear"></div>',
    ));
}

add_action( 'widgets_init', 'theme_widgets_init' );	


if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'imaj', 178, 178, true ); 

}	

if ( function_exists( 'wp_nav_menu' ) ){
	if (function_exists('add_theme_support')) {
		add_theme_support('nav-menus');
		add_action( 'init', 'register_my_menus' );
		function register_my_menus() {
			register_nav_menus(
				array(
					'top-links' => __( 'Top Links' ),
					'main-menu' => __( 'Main Menu' ),
					'footer-links' => __( 'Footer Links' )
				)
			);
		}
	}
}
/* Paginate */
function emm_paginate($args = null) {
	$defaults = array(
		'page' => null, 'pages' => null, 
		'range' => 3, 'gap' => 3, 'anchor' => 1,
		'before' => '<div class="emm-paginate">', 'after' => '</div>',
		'title' => __('Pages:'),
		'nextpage' => __('&raquo;'), 'previouspage' => __('&laquo'),
		'echo' => 1
	);

	$r = wp_parse_args($args, $defaults);
	extract($r, EXTR_SKIP);

	if (!$page && !$pages) {
		global $wp_query;

		$page = get_query_var('paged');
		$page = !empty($page) ? intval($page) : 1;

		$posts_per_page = intval(get_query_var('posts_per_page'));
		$pages = intval(ceil($wp_query->found_posts / $posts_per_page));
	}
	
	$output = "";
	if ($pages > 1) {	
		$output .= "$before<span class='emm-title'>$title</span>";
		$ellipsis = "<span class='emm-gap'>...</span>";

		if ($page > 1 && !empty($previouspage)) {
			$output .= "<a href='" . get_pagenum_link($page - 1) . "' class='emm-prev'>$previouspage</a>";
		}
		
		$min_links = $range * 2 + 1;
		$block_min = min($page - $range, $pages - $min_links);
		$block_high = max($page + $range, $min_links);
		$left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
		$right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;

		if ($left_gap && !$right_gap) {
			$output .= sprintf('%s%s%s', 
				emm_paginate_loop(1, $anchor), 
				$ellipsis, 
				emm_paginate_loop($block_min, $pages, $page)
			);
		}
		else if ($left_gap && $right_gap) {
			$output .= sprintf('%s%s%s%s%s', 
				emm_paginate_loop(1, $anchor), 
				$ellipsis, 
				emm_paginate_loop($block_min, $block_high, $page), 
				$ellipsis, 
				emm_paginate_loop(($pages - $anchor + 1), $pages)
			);
		}
		else if ($right_gap && !$left_gap) {
			$output .= sprintf('%s%s%s', 
				emm_paginate_loop(1, $block_high, $page),
				$ellipsis,
				emm_paginate_loop(($pages - $anchor + 1), $pages)
			);
		}
		else {
			$output .= emm_paginate_loop(1, $pages, $page);
		}

		if ($page < $pages && !empty($nextpage)) {
			$output .= "<a href='" . get_pagenum_link($page + 1) . "' class='emm-next'>$nextpage</a>";
		}

		$output .= $after;
	}

	if ($echo) {
		echo $output;
	}

	return $output;
}

function emm_paginate_loop($start, $max, $page = 0) {
	$output = "";
	for ($i = $start; $i <= $max; $i++) {
		$output .= ($page === intval($i)) 
			? "<span class='emm-page emm-current'>$i</span>" 
			: "<a href='" . get_pagenum_link($i) . "' class='emm-page'>$i</a>";
	}
	return $output;
}


function imaj_image(){

if ( has_post_thumbnail() ) {
	 ?><div class="post-thumbnail"><?php the_post_thumbnail( 'imaj', array('class' => 'img post-image-prelaoder') );?></div><?php
} else {
	?>
	<div class="no-post-thumbnail"><img class="post-image-prelaoder" src="<?php bloginfo('template_directory'); ?>/images/tow-no-image.png"></div>
	<?php
};

}

add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 10; }

function new_excerpt_more($more) {
       global $post;
	return ' <a class="read-more" href="'. get_permalink($post->ID) . '"> </a>';
}
add_filter('excerpt_more', 'new_excerpt_more');


/* CallBack functions for menus in case of earlier than 3.0 Wordpress version or if no menu is set yet*/

function toplinks(){ ?>
		<div id="topLinks">
			<ul>
			
					<li><a <?php if(is_home()) echo 'id="home"'; ?> href="<?php bloginfo('url'); ?>/">home</a></li>
					<?php wp_list_pages('title_li=')?>

					
			</ul>
			
		</div>
<?php }

function footerlinks(){ ?>
		<div id="footerLinks">
			<ul>
					<li><a href="<?php bloginfo('url'); ?>/">home</a></li>
					<?php wp_list_pages('title_li=') ?>
			</ul>
		</div>
<?php }

function mainmenu(){ ?>
		<div id="topMenu">
			<ul class="sf-menu">
				<?php wp_list_categories('hide_empty=1&exclude=1&title_li='); ?>
			</ul>
		</div>
<?php }

function content($num) {  
		$theContent = get_the_content();  
		$output = preg_replace('/<img[^>]+./','', $theContent);  
		$limit = $num+1;  
		$content = explode(' ', $output, $limit);  
		array_pop($content);  
		$content = implode(" ",$content)."...";  
		echo $content;  
}


function post_is_in_descendant_category( $cats, $_post = null )
{
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$descendants = get_term_children( (int) $cat, 'category');
		if ( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
  	

     <div id="comment-<?php comment_ID(); ?>">
	 <div class="cocomment"><div class="comm-arrow"></div>
      <div class="comment-author vcard">
	  <div class="comment-meta commentmetadata">
	   <?php echo get_avatar($comment,$size='64',$default='http://www.gravatar.com/avatar/61a58ec1c1fba116f8424035089b7c71?s=64&d=&r=G' ); ?>
	  <?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?> <br /><?php printf(__('<strong>%s</strong> says:'), get_comment_author_link()) ?><?php edit_comment_link(__('(Edit)'),'  ','') ?></div>
         
      </div>
	  
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="text"><?php comment_text() ?></div>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
	  </div>
     </div>
<?php }

add_action('admin_menu', 'cici_theme_page');

function cici_theme_page ()
{
global $themename;

	if ( count($_POST) > 0 && isset($_POST['cici_settings']) )
	{
		$options = array ( 'style','logo_img','logo_alt','logo_txt', 'logo_tagline', 'tagline_width', 'contact_email','ads','ads1', 'advertise_page', 'twitter_link', 'facebook_link', 'about_tit', 'about_txt','image_url1','banner_url1','image_url2','banner_url2','image_url3','banner_url3','image_url4','banner_url4','banner_url5','image_url5', 'banner_url6','image_url6','banner_url7','image_url7','analytics','video','videos','featured', 'slide_cat', 'slide_num', 'slide','ss_color');
		
		foreach ( $options as $opt )
		{
			delete_option ( 'cici_'.$opt, $_POST[$opt] );
			add_option ( 'cici_'.$opt, $_POST[$opt] );	
		}			
		 
	}
	add_theme_page(__($themename)." Panel", __($themename)." Panel", 'edit_themes', basename(__FILE__), 'cici_settings');	
}

function cici_settings ()
{
global $themename, $shortname, $options;
?>
<div class="wrap">
	<h2><?php echo $themename ?> Options Panel</h2>
	
<form method="post" action="">
	<table class="form-table">
		<!-- General settings -->
		<tr>
			<th colspan="2"><strong>General Settings</strong></th>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_img">Logo image (full path to image)</label></th>
			<td>
				<input name="logo_img" type="text" id="logo_img" value="<?php echo get_option('cici_logo_img'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_alt">Logo image ALT text</label></th>
			<td>
				<input name="logo_alt" type="text" id="logo_alt" value="<?php echo get_option('cici_logo_alt'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_txt">Text logo</label></th>
			<td>
				<input name="logo_txt" type="text" id="logo_txt" value="<?php echo get_option('cici_logo_txt'); ?>" class="regular-text" />
				<br /><em>Leave this empty if you entered an image as logo</em>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_tagline">Logo Tag Line</label></th>
			<td>
				<input name="logo_tagline" type="text" id="logo_tagline" value="<?php echo get_option('cici_logo_tagline'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="tagline_width">Tag Line Box Width (px)</label><br /><em style="font-size:11px">Default width: 300px</em></th>
			<td>
				<input name="tagline_width" type="text" id="tagline_width" value="<?php echo get_option('cici_tagline_width'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="contact_email">Email Address for Contact Form</label></th>
			<td>
				<input name="contact_email" type="text" id="contact_email" value="<?php echo get_option('cici_contact_email'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="twitter_link">Twitter ID</label></th>
			<td>
				<input name="twitter_link" type="text" id="twitter_link" value="<?php echo get_option('cici_twitter_link'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="facebook_link">Facebook link</label></th>
			<td>
				<input name="facebook_link" type="text" id="facebook_link" value="<?php echo get_option('cici_facebook_link'); ?>" class="regular-text" />
			</td>
		</tr>
		<!-- Sidebar ABout Box-->
		<tr>
			<th colspan="2"><strong>Text Widget</strong></th>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="about_txt">Text</label></th>
			<td>
				<textarea cols="60" rows="5" name="about_txt" type="text" id="about_txt" class="regular-text" /><?php echo get_option('cici_about_txt'); ?></textarea>
			</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Ads Sidebar 125x125 Box Settings</strong></th>
		</tr>
		<tr>
			<th><label for="ads">Ads Section Enabled:</label></th>
			<td>
				<select name="ads" id="ads">
					<option value="no" <?php if(get_option('cici_ads') == 'no'){?>selected="selected"<?php }?>>No</option>
					<option value="yes" <?php if(get_option('cici_ads') == 'yes'){?>selected="selected"<?php }?>>Yes</option>
				</select> 
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="image_url1">Image URL 1</label></th>
			<td>
				<input name="image_url1" type="text" id="image_url1" value="<?php echo get_option('cici_image_url1'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="banner_url1">Banner URL 1</label></th>
			<td>
				<input name="banner_url1" type="text" id="banner_url1" value="<?php echo get_option('cici_banner_url1'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="image_url2">Image URL 2</label></th>
			<td>
				<input name="image_url2" type="text" id="image_url2" value="<?php echo get_option('cici_image_url2'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="banner_url2">Banner URL 2</label></th>
			<td>
				<input name="banner_url2" type="text" id="banner_url2" value="<?php echo get_option('cici_banner_url2'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="image_url3">Image URL 3</label></th>
			<td>
				<input name="image_url3" type="text" id="image_url3" value="<?php echo get_option('cici_image_url3'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="banner_url3">Banner URL 3</label></th>
			<td>
				<input name="banner_url3" type="text" id="banner_url3" value="<?php echo get_option('cici_banner_url3'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="image_url4">Image URL 4</label></th>
			<td>
				<input name="image_url4" type="text" id="image_url4" value="<?php echo get_option('cici_image_url4'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="banner_url4">Banner URL 4</label></th>
			<td>
				<input name="banner_url4" type="text" id="banner_url4" value="<?php echo get_option('cici_banner_url4'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="image_url6">Image URL 5</label></th>
			<td>
				<input name="image_url6" type="text" id="image_url6" value="<?php echo get_option('cici_image_url6'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="banner_url6">Banner URL 5</label></th>
			<td>
				<input name="banner_url6" type="text" id="banner_url6" value="<?php echo get_option('cici_banner_url6'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="image_url7">Image URL 6</label></th>
			<td>
				<input name="image_url7" type="text" id="image_url7" value="<?php echo get_option('cici_image_url7'); ?>" class="regular-text" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="banner_url7">Banner URL 6</label></th>
			<td>
				<input name="banner_url7" type="text" id="banner_url7" value="<?php echo get_option('cici_banner_url7'); ?>" class="regular-text" />
			</td>
		</tr>
		<!-- Video -->
		<tr>
			<th colspan="2"><strong>Sidebar Video</strong></th>
		</tr>
		<tr>
			<th><label for="videos">Video Section Enabled:</label></th>
			<td>
				<select name="videos" id="videos">
					<option value="no" <?php if(get_option('cici_videos') == 'no'){?>selected="selected"<?php }?>>No</option>
					<option value="yes" <?php if(get_option('cici_videos') == 'yes'){?>selected="selected"<?php }?>>Yes</option>
				</select> 
			</td>
		</tr>
		<tr>
			<th><label for="video">Paste the video embed code</label><br /><em style="font-size:11px">Please resize the video to width=280px and height=220px</em></th>
			<td>
				<textarea name="video" id="video" rows="7" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('cici_video')); ?></textarea>
			</td>
		</tr>
		
		<!-- Google Analytics -->
		<tr>
			<th><label for="ads">Google Analytics code:</label></th>
			<td>
				<textarea name="analytics" id="analytics" rows="7" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('cici_analytics')); ?></textarea>
			</td>
		</tr>
		
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="cici_settings" value="save" style="display:none;" />
	</p>
</form>

</div>
<?php }
function get_first_image() {
global $post, $posts;
$first_img = '';
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
$first_img = $matches [1] [0];
if(empty($first_img)){ //Defines a default image
$first_img = "/images/default.jpg";
}
return $first_img;
} ?>
<?php
add_action( 'wp_print_scripts', 'my_deregister_javascript', 100 );
function my_deregister_javascript() {
if ( is_home() ) {
wp_deregister_script( 'contact-form-7' );
}
}

function dimox_breadcrumbs() {
 
  $delimiter = '&raquo;';
  $home = 'Home'; // text for the 'Home' link
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  if ( !is_home() && !is_front_page() || is_paged() ) {
 
    echo '<div id="crumbs">';
 
    global $post;
    $homeLink = get_bloginfo('url');
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
 
    echo '</div>';
 
  }
} // end dimox_breadcrumbs()
?>