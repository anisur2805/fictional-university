<?php

add_action( 'rest_api_init', 'fu_rest_api_init' );
function fu_rest_api_init() {
	register_rest_field(
		'post',
		'author_name',
		array(
			'get_callback' => 'fu_get_author_name',
		)
	);
	register_rest_route(
		'university/v1',
		'search',
		array(
			'methods'  => 'GET',
			'callback' => 'fu_search',
		)
	);
}

function fu_get_author_name( $object ) {
	return get_the_author_meta( 'display_name', $object['author'] );
}

function fu_search( $data ) {
	$search_query = new WP_Query(
		array(
			'post_type'      => array( 'post', 'page', 'professor', 'event', 'campus', 'program' ),
			's'              => sanitize_text_field( $data['term'] ),
			'posts_per_page' => -1,
		)
	);

	$results = array(
		'generalInfo' => array(),
		'professors'  => array(),
		'events'      => array(),
		'campuses'    => array(),
		'programs'    => array(),
	);
	while ( $search_query->have_posts() ) {
		$search_query->the_post();

		if ( get_post_type() === 'post' || get_post_type() === 'page' ) {
			$results['generalInfo'][] = array(
				'title' => get_the_title(),
				'url'   => get_the_permalink(),
				'image' => get_the_post_thumbnail_url( 0, 'professor-landscape' ),
			);
		}

		if ( get_post_type() === 'professor' ) {
			$results['professors'][] = array(
				'title' => get_the_title(),
				'url'   => get_the_permalink(),
				'image' => get_the_post_thumbnail_url( 0, 'professor-landscape' ),
			);
		}

		if ( get_post_type() === 'event' ) {
			$results['events'][] = array(
				'title' => get_the_title(),
				'url'   => get_the_permalink(),
				'image' => get_the_post_thumbnail_url( 0, 'professor-landscape' ),
			);
		}

		if ( get_post_type() === 'campus' ) {
			$results['campuses'][] = array(
				'title' => get_the_title(),
				'url'   => get_the_permalink(),
				'image' => get_the_post_thumbnail_url( 0, 'professor-landscape' ),
			);
		}

		if ( get_post_type() === 'program' ) {
			$results['programs'][] = array(
				'title' => get_the_title(),
				'url'   => get_the_permalink(),
				'image' => get_the_post_thumbnail_url( 0, 'professor-landscape' ),
			);
		}
	}
	return $results;
}
