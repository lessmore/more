<?php get_header(); ?>
	
	
	
	<!-- begin content -->
	<div id="content" style="<?php if(is_home() || is_category() || is_archive()){ echo 'margin:40px 0';}?>"	class="clearfix">
	
<!-- begin contentLeft -->
		<div id="contentLeft" style="width:100%">  

<!-- tooltip element -->		
			<!-- begin blog item -->
			<?php $counter =0; ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php
			  ++$counter;
			  if ($counter == 4){
			  	$poststyle = 'style=margin-right:0;';
			  } else {
			  	$poststyle = ' ';
				}
			  if($counter == 5) {
				$postclass = 'clear';
				$counter = 1;
			  } 
			  else { echo $postclass = ' ';}
			  
			
			  
			  ?>
			<div class="postBox-home <?php echo $postclass;?>" <?php echo $poststyle; ?>>
				<a href="<?php the_permalink();?>"><?php imaj_image(); ?></a>
				<div class="entry">
			<div class="title"><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1></div>
			<div class="clear"></div>
				<?php the_excerpt(); ?>
				</div>
				
                
				
				</div>	
			<!-- end blog item -->
			<?php endwhile; ?>
           <div class="clear"></div>
		
		<?php if (function_exists("emm_paginate")) {
    emm_paginate();
} ?>	

	<?php else : ?>

		<p>Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>
		</div>
		<!-- end contentLeft -->

<?php get_footer(); ?>