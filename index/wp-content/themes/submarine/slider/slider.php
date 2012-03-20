    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/slider/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/slider/style.css" type="text/css" media="screen" />
    <div id="wrapper">
        <div id="slider-wrapper">
        
            <div id="slider" class="nivoSlider">
  <!-- First Column Starts -->
            		<?php $recent = new WP_Query("showposts=3"); while($recent->have_posts()) : $recent->the_post();?>
                    <a href="<?php the_permalink(); ?>" title="" rel="bookmark" />
                    <?php
					the_post_thumbnail( 'slider-post-thumbnail', array('title' => '' ));
					 ?>
                    </a>             
                	<?php endwhile; ?>
        
          
            <!-- First Column Ends -->                
            </div>
          
        
        </div>

    </div>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/slider/scripts/jquery-1.4.3.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/slider/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
    </script>
