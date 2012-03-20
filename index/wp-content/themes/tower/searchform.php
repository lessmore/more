<form id="searchform" action="" method="get">
	<input type="text" value="Search here..." onfocus="if (this.value == 'Search here...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search here...';}"  value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" />
			<input type="submit" id="searchsubmit" value=""/>	
			</form>