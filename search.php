<?php get_header();

fictional_university_page_banner(
	array(
		'title'    => 'Search result for: ' . esc_html( get_search_query() ),
		'subtitle' => 'Keep up to date with the latest news',
	)
);
?>

<div class="container container--narrow page-section">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		}
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}
	echo paginate_links();

	?>
</div>

<?php
get_footer();
