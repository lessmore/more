<?php get_header(); ?>
<?php if (have_posts()) : ?>
	<h1 class="pagetitle"><?php printf( __("Search results for '%s'", "vigilance"), get_search_query()); ?></h1>
	<img class="archive-comment"src="<?php echo get_template_directory_uri(); ?>/images/comments-bubble-archive.gif" width="17" height="14" alt="Comments"/>
	<div class="entries">
		<ul>
		<?php while (have_posts()) : the_post(); ?>
			<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr( sprintf( __( 'Permanent Link to %s', 'vigilance' ), the_title_attribute( 'echo=false' ) ) ); ?>"><span class="comments_number"><?php comments_number( '0', '1', '%', '' ); ?></span><span class="archdate"><?php the_time( __( 'n.j.y', 'vigilance' )); ?></span><?php the_title(); ?></a></li>
		<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		</ul>
	</div><!--end entries-->
	<div class="navigation">
		<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'vigilance' )); ?></div>
		<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'vigilance' )); ?></div>
	</div><!--end navigation-->
<?php else : ?>
	<h1 class="pagetitle"><?php printf( __("Search results for '%s'", "vigilance"), get_search_query()); ?></h1>
	<div class="entry">
		<p><?php printf( __( 'Sorry your search for "%s" did not turn up any results. Please try again.', 'vigilance' ), get_search_query());?></p>
		<?php get_search_form(); ?>
	</div><!--end entry-->
<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
