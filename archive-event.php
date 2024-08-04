<?php
	get_header();

	fictional_university_page_banner(
		array(
			'title'    => get_the_archive_title(),
			'subtitle' => get_the_archive_description(),
		)
	);
	?>

<div class="container container--narrow page-section">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content-event' );
	}
	echo paginate_links();

	echo '<p>You can visit the <a href="' . site_url( '/past-events' ) . '">past events page</a> to view all past events.</p>';

	?>
</div>

<?php
get_footer();
