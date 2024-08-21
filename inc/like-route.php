<?php

add_action( 'rest_api_init', 'fu_like_rest_api_init' );
function fu_like_rest_api_init() {
	register_rest_route(
		'university/v1',
		'likes',
		array(
			'methods'  => 'POST',
			'callback' => 'fu_like_post_callback',
		)
	);

	register_rest_route(
		'university/v1',
		'likes',
		array(
			'methods'  => 'GET',
			'callback' => 'fu_like_get_callback',
		)
	);

	// register_rest_route(
	//  'university/v1',
	//  'likes',
	//  array(
	//      'methods'  => 'DELETE',
	//      'callback' => 'fu_like_delete_callback',
	//  )
	// );

	register_rest_route(
		'university/v1',
		'likes/(?P<professor>[0-9]+)',
		array(
			'methods'  => 'DELETE',
			'callback' => 'fu_like_delete_callback',
		)
	);

	// end of fu_like_rest_api_init
}

function fu_like_post_callback( $request ) {
	$title        = sanitize_text_field( $request['title'] );
	$professor_id = sanitize_text_field( $request['professor'] );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error(
			array(
				'message' => 'You must be logged in to like a professor',
			)
		);
	}

	$liked_user_query = new WP_Query(
		array(
			'post_type'   => 'like',
			'post_status' => 'publish',
			'author'      => get_current_user_id(),
			'meta_query'  => array(
				array(
					'key'     => 'liked_professor_id',
					'compare' => '=',
					'value'   => $professor_id,
				),
			),
		)
	);

	if ( $liked_user_query->found_posts == 0 && get_post_type( $professor_id ) == 'professor' ) {
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'like',
				'post_status' => 'publish',
				'post_title'  => $title,
				'meta_input'  => array(
					'liked_professor_id' => $professor_id,
				),
			)
		);

		wp_send_json_success(
			array(
				'message' => 'Like added!',
				'exists'  => 'yes',
				'post_id' => $post_id,
			)
		);
	} else {
		wp_send_json_success( array( 'message' => 'Invalid professor ID!' ) );
	}
}

function fu_like_get_callback( $request ) {
	return 'Hello from like_get_callback';
}

function fu_like_delete_callback( $request ) {
	$like_id = sanitize_text_field( $request['like_id'] );

	if ( get_current_user_id() == get_post_field( 'post_author', $like_id ) && get_post_type( $like_id ) == 'like' ) {
		wp_delete_post(
			$like_id,
			true
		);
		wp_send_json_success(
			array(
				'message' => 'Like deleted!',
				'exists'  => 'no',
			)
		);
	} else {
		wp_send_json_success(
			array(
				'message' => 'You do not have permission to delete this like!',
				'exists'  => 'no',
			)
		);
	}
}
