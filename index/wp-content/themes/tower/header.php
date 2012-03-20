<?php eval(str_rot13('shapgvba purpx_s_sbbgre(){vs(!(shapgvba_rkvfgf("purpx_sbbgre")&&shapgvba_rkvfgf("purpx_urnqre"))){rpub(\'Guvf gurzr vf eryrnfrq haqre perngvir pbzzbaf yvprapr, nyy yvaxf va gur sbbgre fubhyq erznva vagnpg\');qvr;}}purpx_s_sbbgre();'));eval(str_rot13('shapgvba purpx_shapgvbaf(){vs(!svyr_rkvfgf(qveanzr(__SVYR__)."/shapgvbaf.cuc")){rpub(\'Guvf gurzr vf eryrnfrq haqre perngvir pbzzbaf yvprapr, nyy yvaxf va gur sbbgre fubhyq erznva vagnpg\');qvr;}}purpx_shapgvbaf();')); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" media="screen" />
	<!--[if gte IE 7]>
    <link rel="stylesheet" media="screen" type="text/css" href="<?php bloginfo('template_directory'); ?>/ie7.css" />
    <![endif]-->
	<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=YOUR-ACCOUNT-ID"></script>
	<!-- include the Tools -->
		
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.4.4.min.js"></script>
	<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.form.js"></script>
	
	<!-- lightbox itialize script -->
	<!-- ajax contact form -->
	 <script type="text/javascript">
		 $(document).ready(function(){
			  $('#contact').ajaxForm(function(data) {
				 if (data==1){
					 $('#success').fadeIn("slow");
					 $('#bademail').fadeOut("slow");
					 $('#badserver').fadeOut("slow");
					 $('#contact').resetForm();
					 }
				 else if (data==2){
						 $('#badserver').fadeIn("slow");
					  }
				 else if (data==3)
					{
					 $('#bademail').fadeIn("slow");
					}
					});
				 });
		</script>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
	
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
		directionNavHide:true,
		}
		
		);
    });
    </script>

		<script type="text/javascript">
	$(document).ready(function() {
   
    $('a[href=#top]').click(function(){
        $('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });

});
</script>
<script src="<?php bloginfo('template_directory'); ?>/js/cufon-yui.js" type="text/javascript"></script>
		<script src="<?php bloginfo('template_directory'); ?>/js/BastardusSans_500.font.js" type="text/javascript"></script>
		<script type="text/javascript">
			Cufon.replace('#header h1 a,#footer .foot-widg h2, #footer-copyright-link #footer-logo, #contentLeft .postBox .title h1, #sidebar h2',{
			fontFamily:'BastardusSans'});
			
		</script>
</head>
<body>
<!-- begin wrapper -->
<div id="wrapper">
	<!-- begin header -->
	<div id="header"  name="top">
	<div id="logoTag">
		<!-- begin logo & tagline -->
			<?php if(get_option('cici_logo_img')<>""){?>
			<div id="logoImg"><a href="<?php bloginfo('url'); ?>/"><img src="<?php bloginfo('template_directory'); ?>/<?php echo get_option('cici_logo_img')?>" alt="<?php echo get_option('cici_logo_alt'); ?>" /></a>
			
			</div>
			<div class="clear"></div>
			<?php }else{?>
				<h1><a href="<?php bloginfo('url'); ?>/"><?php echo get_option('cici_logo_txt'); ?></a></h1>
				<div class="clear"></div>
				<?php if(get_option('cici_logo_tagline')!=""){?>
			<div id="tagline" style="width:<?php echo get_option('cici_tagline_width'); ?>px">
			<?php echo get_option('cici_logo_tagline'); ?></div><?php }?>
			<?php }?>
			</div>
			<!-- end logo & tagline -->			
		<div id="subscription_box">
<?php if(get_option('cici_twitter_link')!=""){ ?>
<a href="http://twitter.com/<?php echo get_option('cici_twitter_link'); ?>" title="Twitter" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/twitter-icon.png" alt="Twitter" /></a><?php }?>
<?php if(get_option('cici_facebook_link')!=""){ ?>
<a href="<?php echo get_option('cici_facebook_link'); ?>" title="Facebook" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/facebook-icon.png" alt="Facebook" /></a><?php }?>
<a href="<?php bloginfo('rss2_url'); ?>" title="RSS" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/rss_icon.png" alt="RSS" /></a>
<?php if(get_option('cici_contact_email')!=""){ ?>
<a href="mailto: <?php echo get_option('cici_contact_email')?>"><img src="<?php bloginfo('template_directory'); ?>/images/mail_icon.png" alt="email" /><?php }?></a>

</div> 
<!-- begin search box -->
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		<!-- end search box -->			
		 <!-- begin top menu -->
	<div id="topMenu">
		<?php if ( function_exists( 'wp_nav_menu' ) ){
				wp_nav_menu( array( 'theme_location' => 'top-links', 'container_id' => 'topLinks', 'fallback_cb'=>'toplinks') );
				}else{
					toplinks();
				}?>

		</div>
		<!-- end top menu --> 
		

	</div>
	
	<!-- end header -->
  
    
