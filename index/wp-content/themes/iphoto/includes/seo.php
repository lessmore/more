<?php
 if ( is_home() ) {;echo '<title>';bloginfo('name');;echo ' | ';bloginfo('description');;echo '</title>';};echo '';if ( is_search() ) {;echo '<title>搜索结果 | ';bloginfo('name');;echo '</title>';};echo '';if ( is_single() ) {;echo '<title>';echo trim(wp_title('',0));;echo ' | ';bloginfo('name');;echo '</title>';};echo '';if ( is_page() ) {;echo '<title>';echo trim(wp_title('',0));;echo ' | ';bloginfo('name');;echo '</title>';};echo '';if ( is_category() ) {;echo '<title>';single_cat_title();;echo ' | ';bloginfo('name');;echo '</title>';};echo '';if ( is_month() ) {;echo '<title>';the_time('F');;echo ' | ';bloginfo('name');;echo '</title>';};echo '';if (function_exists('is_tag')) {if ( is_tag() ) {;echo '<title>';single_tag_title('',true);;echo ' | ';bloginfo('name');;echo '</title>';};echo ' ';};echo '';
if (!function_exists('utf8Substr')) {
function utf8Substr($str,$from,$len)
{
return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
'$1',$str);
}
}
if ( is_single() ){
if ($post->post_excerpt) {
$description  = $post->post_excerpt;
}else {
if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($post->post_content,'<p>')),$result)){
$post_content = $result['1'];
}else {
$post_content_r = explode("\n",trim(strip_tags($post->post_content)));
$post_content = $post_content_r['0'];
}
$description = utf8Substr($post_content,0,220);
}
$keywords = '';
$tags = wp_get_post_tags($post->ID);
foreach ($tags as $tag ) {
$keywords = $keywords .$tag->name .',';
}
}
;echo '';echo "\n";;echo '';if ( is_single() ) {;echo '<meta name="description" content="';echo trim($description);;echo '" />
<meta name="keywords" content="';echo rtrim($keywords,',');;echo '" />
';};echo '
';if ( is_home() ) {;echo '<meta name="keywords" content="';echo get_option('iphoto_keywords');;echo '" />
<meta name="description" content="';echo get_option('iphoto_description');;echo '" />
';}?>
