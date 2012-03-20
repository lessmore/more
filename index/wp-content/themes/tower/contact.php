<?php
/*
Template Name: Contact
*/
?>

<?php get_header(); ?>
<!-- begin content -->

	<div id="content" class="clearfix">
<?php if(is_home()){?>
		<span>Recent posts</span>
	<?php
	} elseif (function_exists('dimox_breadcrumbs')) {dimox_breadcrumbs();} ?>
	
			<!-- end box about -->
<!-- begin contentLeft -->
		<div id="contentLeft">
		<div class="postBox"><div class="top-target"></div><div class="bottom-target"></div>
			<h1>Contact Us</h1>
<div class="clear"></div>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor 
Lorem ipsum dolor sit amet, consectetur.</p>
			
			<p id="success" class="successmsg" style="display:none;">Your email has been sent! Thank you!</p>

			<p id="bademail" class="errormsg" style="display:none;">Please enter your name and a valid email address.</p>
			<p id="badserver" class="errormsg" style="display:none;">Your email failed. Try again later.</p>

			<form id="contact" action="<?php bloginfo('template_url'); ?>/sendmail.php" method="post">
			<label for="name">Your name: *</label>
				<input type="text" id="nameinput" name="name" value=""/>
			<label for="email">Your email: *</label>

				<input type="text" id="emailinput" name="email" value=""/>
			<label for="comment">Your message: *</label>
				<textarea cols="20" rows="7" id="commentinput" name="comment"></textarea><br />
			<input type="submit" id="submitinput" class="submit" value="Send &raquo;"/>
			<input type="hidden" id="receiver" name="receiver" value="<?php echo get_option('cici_contact_email')?>"/>
			</form>
			
			</div>
		</div>
		<!-- end contentLeft -->

<!-- begin sidebarBox -->
		<div id="sidebarBox">	
			<?php get_sidebar(); ?>	
			
			</div>
<!-- end sidebarBox -->


<?php get_footer(); ?>


