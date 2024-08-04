<?php
	get_header();

	fictional_university_page_banner(
		array(
			'title'    => 'Our Campuses',
			'subtitle' => 'We have several different campuses.',
		)
	);
	?>

<div class="container container--narrow page-section">
	<?php
	while ( have_posts() ) {
		the_post();
		$location = get_field( 'map_location' );
		?>
		<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
		<?php
	}
	echo paginate_links();
	?>
</div>

<?php
get_footer();
