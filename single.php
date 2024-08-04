<?php get_header();

while ( have_posts() ) {
	the_post();

	fictional_university_page_banner(
		array(
			'title'    => get_the_title(),
			'subtitle' => 'Learn how the school of your dreams got started.',
		)
	);
	?>

<div class="container container--narrow page-section">
	<div class="metabox metabox--position-up metabox--with-home-link">
		<p>
			<a class="metabox__blog-home-link" href="<?php echo home_url( '/blog' ); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php _e( 'Blog', 'fictional-university' ); ?></a> <span>Posted by <?php the_author_posts_link(); ?> on <?php the_time( 'F j, Y' ); ?> in <?php echo get_the_category_list( ', ' ); ?></span></span>
		</p>
	</div>

	<div class="generic-content">
		<?php the_content(); ?>
	</div>
</div>
	<?php
}

get_footer();