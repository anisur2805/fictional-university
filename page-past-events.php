<?php get_header();

fictional_university_page_banner(
	array(
		'title' => 'Past Events',
	)
);
?>

<div class="container container--narrow page-section">
	<?php
	$event_args  = array(
		'paged'      => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		'post_type'  => 'event',
		'meta_key'   => 'event_date',
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'event_date',
				'compare' => '<',
				'value'   => date( 'Ymd' ),
				'type'    => 'numeric',
			),
		),
	);
	$event_query = new WP_Query( $event_args );
	while ( $event_query->have_posts() ) :
		$event_query->the_post();
		get_template_part( 'template-parts/content-event' );
	endwhile;
	echo paginate_links(
		array(
			'total' => $event_query->max_num_pages,
		)
	);

	?>
</div>

<?php
get_footer();
