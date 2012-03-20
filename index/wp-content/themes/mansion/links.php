<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<div id="content">

<h2><?php esc_html_e('Links', 'gpp'); ?>:</h2>
<ul>
<?php wp_list_bookmarks(); ?>
</ul>

</div>

<?php get_footer(); ?>
