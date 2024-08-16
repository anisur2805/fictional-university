<form class="search-form" role="search" method="get" action="<?php echo site_url( '/' ); ?>">
	<label class="headline headline--medium">Perform a new search</label>
	<div class="search-form-row">
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'fictional-university' ); ?></span>
		<input type="search" placeholder="<?php echo esc_attr_x( 'What are you looking for?', 'placeholder', 'fictional-university' ); ?>" class="s" aria-label="<?php echo esc_attr_x( 'Search for:', 'label', 'fictional-university' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'fictional-university' ); ?>" />
	</div>
</form>