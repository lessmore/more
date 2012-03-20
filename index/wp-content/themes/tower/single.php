<?php
get_header();
?>
	<!-- begin content -->
	<div class="clear"></div>
	<div id="content" class="clearfix">
<?php if(is_home()){?>
		<span>Recent posts</span>
	<?php
	} elseif (function_exists('dimox_breadcrumbs')) {dimox_breadcrumbs();} ?>
<!-- begin contentLeft -->
	<div id="contentLeft" class="clearfix">
		<?php 
		if (have_posts()) : 
		while (have_posts()) : the_post(); ?>
			<!-- blog item --><div class="postBox">
					<div class="title"><h1><?php the_title(); ?></h1></div>
<div class="clear"></div>
<div class="metadata">Posted by <span><?php the_author(); ?></span> in <span><?php the_category(', '); ?></span> on <span><?php the_time('F j, Y') ?></span> </div>
<div class="clear"></div>
				<?php the_content(); ?> 
				
			
		<!-- end postBox -->
		<div id="post-nav"><span style="float:left;width:225px;"><?php previous_post_link(); ?></span> <span style="float:right;width:225px;text-align:right;"><?php next_post_link(); ?></span></div>
		<!-- Social Sharing Icons -->
		<div class="social">
			 <p>Did you like this article?<strong> Share it below!</strong></p>
				<div class="addthis_toolbox addthis_32x32_style addthis_default_style socials_btn">
    <a class="addthis_button_facebook"></a>
    <a class="addthis_button_twitter"></a>
    <a class="addthis_button_email"></a>
    <a class="addthis_button_google"></a>
    <a class="addthis_button_compact"></a>
</div>

			</div>
			<div class="clear"></div>
		<div id="related-posts">
<?php $categories = get_the_category($post->ID);
  if ($categories) { $category_ids = array();   
foreach($categories as $individual_category)
$category_ids[] = $individual_category->term_id;
    $args=array(
        'category__in' => $category_ids,
        'post__not_in' => array($post->ID),
        'showposts'=>5, // Corresponds to Number of related posts to be shown.
        'caller_get_posts'=>1
    );
 $my_query = new wp_query($args);
if( $my_query->have_posts() ) {
echo '<h3>Related Posts</h3><ul>';
while ($my_query->have_posts()) {
$my_query->the_post();?><li><a href="<?php the_permalink() ?>"
rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li><?php }
echo '</ul>'; } } $post = $backup; wp_reset_query(); ?>
</div>

<div class="clear"></div>
		
		
		<!-- end Social Sharing Icons -->
		
        <?php comments_template(); ?>
		<?php endwhile; else: ?>

		<p>Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>
	            <div class="clear"></div>
				
			</div>
			</div>
			<!-- end contentLeft -->
	
<!-- begin sidebarBox -->
		<div id="sidebarBox" class="clearfix">
			<?php get_sidebar(); ?>	
		</div>
		<!-- end sidebarBox -->
<div class="clear"></div>

<?php get_footer(); ?>
