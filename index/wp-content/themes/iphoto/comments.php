<?php
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'iphoto'); ?></p> 
	<?php
		return;
	}
?>
<?php if ( have_comments() ) : ?>
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=iphoto_comment&per_page=9999&max_depth=1000'); ?>
	</ol>
 <?php else : ?>
	<?php if ('open' == $post->comment_status) : ?>
	<?php else : ?>
		<p class="nocomments"><?php _e('Comment closed.','iphoto'); ?></p>
	<?php endif; ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?>
<div class="clear"></div>
<div id="respond">
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<h3 class="clearfix"><span id="cancel-comment-reply"><?php cancel_comment_reply_link() ?></span></h3>
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('You need <a href=\"%s\">logged in</a> to comment.'), wp_login_url( get_permalink() )); ?></p>
<?php else : ?>
<?php if ( is_user_logged_in() ) : ?>
<p class="smilies"><?php printf(__('<a href="%1$s">%2$s</a> has logged in,'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account'); ?>"><?php _e('Quit &raquo;','iphoto'); ?></a></p>
<?php else : ?>
<?php if ( $comment_author != "" ) : ?>
<script type="text/javascript">function setStyleDisplay(id, status){document.getElementById(id).style.display = status;}</script>
<?php endif; ?>
<div id="author_info">
	<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1"/>
	<label for="author"><?php _e('<small>Name:</small>', 'iphoto'); ?><?php if ($req) _e(" "); ?></label></p>
	<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2"/>
	<label for="email"><?php _e('<small>Email:</small>', 'iphoto'); ?><?php if ($req) _e(" "); ?></label></p>
	<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
	<label for="url"><?php _e('<small>Page:</small>', 'iphoto'); ?></label></p>
</div>
<div id="author_avatar">
<?php if ( $comment_author != "" ) : ?>
	<script type="text/javascript">setStyleDisplay('author_info','none');</script>
		<?php printf(__('<strong>%s</strong>, welcome back.','iphoto'), $comment_author); ?>
		<span id="show_author_info"><a href="javascript:setStyleDisplay('author_info','');setStyleDisplay('show_author_info','none');setStyleDisplay('hide_author_info','');"><?php _e('Change name &raquo;','iphoto'); ?></a></span>
		<span id="hide_author_info" style="display:none"><a href="javascript:setStyleDisplay('author_info','none');setStyleDisplay('show_author_info','');setStyleDisplay('hide_author_info','none');"><?php _e('Close &raquo;','iphoto'); ?></a></span>
<?php endif; ?>
</div>
<?php endif; ?>
<div id="author_textarea">
<textarea name="comment" id="comment" cols="105" rows="14" tabindex="4" onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','iphoto'); ?>/Ctrl+Enter" />
</div>
<?php comment_id_fields(); ?> 
<?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; ?>
</div>
<?php endif; ?>
