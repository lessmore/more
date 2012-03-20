<?php global $vigilance; ?>
	<div id="footer">
		<p class="right">
			<?php printf(
				__( 'Copyright %1$s %2$s.', 'vigilance' ),
				date( 'Y' ),
				$vigilance->copyrightName()
			); ?>
		</p>
		<p>
			<?php
				printf(
					__( '<a href="%1$s">Vigilance Theme</a> by <a href="%2$s">The Theme Foundry</a>', 'vigilance' ),
					$vigilance->themeurl,
					'http://thethemefoundry.com/'
				);
			?>
		</p>
	</div><!--end footer-->
</div><!--end wrapper-->
<?php wp_footer(); ?>
</body>
</html>