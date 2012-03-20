<?php get_header(); ?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h1><?php the_title(); ?></h1>
				<div id="single-date" class="date"><span><?php the_time(__( 'Y', 'vigilance' )); ?></span> <?php the_time( __( 'F j', 'vigilance' ) ); ?></div>
			</div><!--end post header-->
			<div class="meta clear">
				<div class="tags"><?php the_tags( 'tags: ', ', ', '' ); ?></div>
				<div class="author"><?php printf( __( 'by %s', 'vigilance' ), get_the_author()); ?></div>
			</div><!--end meta-->
			<div class="entry clear">
				<?php if ( has_post_thumbnail() ) {
					the_post_thumbnail( array(250,9999), array( 'class' => 'alignleft' ) );
				} ?>
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit this', 'vigilance' ), '<p style="clear:both">', '</p>' ); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
			<div class="post-footer">
				<p><?php printf( __( 'from &rarr; %s', 'vigilance' ), get_the_category_list( ', ' ) ); ?></p>
			</div><!--end post footer-->
		</div><!--end post-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
	<?php comments_template( '', true); ?>
<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>