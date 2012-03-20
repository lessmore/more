<div class="ads">
<div class="banner">
<?php 
	$ban1 = get_option('cici_image_url1'); 
	$url1 = get_option('cici_banner_url1'); 
	?>
<?php 
	$ban2 = get_option('cici_image_url2'); 
	$url2 = get_option('cici_banner_url2'); 
	?>
<?php 
	$ban3 = get_option('cici_image_url3'); 
	$url3 = get_option('cici_banner_url3'); 
	?>
<?php 
	$ban4 = get_option('cici_image_url4'); 
	$url4 = get_option('cici_banner_url4'); 
	?>
<?php 
	$ban6 = get_option('cici_image_url6'); 
	$url6 = get_option('cici_banner_url6'); 
	?>
<?php 
	$ban7 = get_option('cici_image_url7'); 
	$url7 = get_option('cici_banner_url7'); 
	?>	

<?php if($ban1!=""){?><a href="<?php echo ($url1); ?>" rel="bookmark" title=""><img src="<?php echo ($ban1); ?>" alt="" style="vertical-align:bottom;" /></a><?php }?>
<?php if($ban2!=""){?><a class="right-ad" href="<?php echo ($url2); ?>" rel="bookmark" title=""><img src="<?php echo ($ban2); ?>" alt="" style="vertical-align:bottom;" /></a><?php }?>
<div class="clear"></div>
<?php if($ban3!=""){?><a href="<?php echo ($url3); ?>" rel="bookmark" title=""><img src="<?php echo ($ban3); ?>" alt="" style="vertical-align:bottom;" /></a><?php }?>
<?php if($ban4!=""){?><a class="right-ad" href="<?php echo ($url4); ?>" rel="bookmark" title=""><img src="<?php echo ($ban4); ?>" alt="" style="vertical-align:bottom;" /></a><?php }?>
<div class="clear"></div>
<?php if($ban6!=""){?><a href="<?php echo ($url6); ?>" rel="bookmark" title=""><img src="<?php echo ($ban6); ?>" alt="" style="vertical-align:bottom;" /></a><?php }?>
<?php if($ban7!=""){?><a class="right-ad" href="<?php echo ($url7); ?>" rel="bookmark" title=""><img src="<?php echo ($ban7); ?>" alt="" style="vertical-align:bottom;" /></a><?php }?>

</div>
</div>
