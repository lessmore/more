=== Mansion ===

== Installation ==
This section describes how to install Mansion and get it working. 

	1. Download Mansion from your Graph Paper Press [member dashboard](https://graphpaperpress.com/members/member.php) to your Desktop.
	2. Unzip mansion.zip to your Desktop.	
		* Note: make sure that the extracted folder is `mansion` and that your ZIP file has not created two levels of folders (for example, `mansion/mansion`).
	
	3. Go to `/wp-content/themes/` and make sure that you do not already have a `mansion` folder installed. If you do, then back it up and remove it from `/wp-content/themes/` before uploading your copy of Mansion.
	4. Upload `mansion` to `/wp-content/themes/`.
	5. Activate Mansion through the Appearance -> Themes menu in your WordPress Dashboard.
	6.Go to Settings -> Media and make sure to enter the following values:	
		* Image sizes		
			** Thumbnail size
				*** Width: 200	
				*** Height: 150
				*** [checked] Crop thumbnails to exact dimensions (normally thumbnails are proportional)
				
			** Medium size
				*** Max Width: 495
				*** Max Height: 0
				
			** Large size
				*** Max Width: 950
				*** Max Height: 0
				
		
		* Embeds
			** [checked] When possible, embed the media content from a URL directly onto the page. For example: links to Flickr and YouTube.
			** Maximum embed size
				*** Width: 495
				*** Height: 0


= Thumbnails =

Every Post needs to have a Featured Image assigned to it.  You can assign a Featured Image by uploading an image to the Post, and then click the "Use as featured image" button to make the image the Featured Image for that post.  [Watch a video tutorial](http://vimeo.com/8462281).

If you are migrating from an old theme to a new theme and your thumbnails look squished or distorted, you might need to re-upload the image you plan on using for the post thumbnail. This is because WordPress creates your image sizes based on the dimensions you specified above. Old thumbnails will not be automatically resized.  You can regenerate your thumbnails with the [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) WordPress plugin.


= Video =

Paste the link to your video onto any page or post and WordPress will automatically embed the video from the link.

= Menus = 

This theme has built-in support for WordPress' new Menu system, which will be released in version 3.0. This new system, which can be accessed at Appearance -> Menus, allows you to drag and drop menu items with total ease. You can also add custom links.