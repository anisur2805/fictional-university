<?php

require_once get_theme_file_path( '/inc/rest-api.php' );
require_once get_theme_file_path( '/inc/cpt-manager.php' );

function fictional_university_assets() {
	wp_enqueue_style( 'fictional-university-google-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i" rel="stylesheet' );
	wp_enqueue_style( 'fictional-university-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_script( 'fictional-university-index', get_theme_file_uri( '/build/index.js' ), array( 'jquery' ), microtime(), true );
	wp_enqueue_style( 'fictional-university-style', get_stylesheet_uri() );
	wp_enqueue_style( 'fictional-university-index', get_theme_file_uri( '/build/index.css' ), null, microtime() );
	wp_enqueue_style( 'fictional-university-style-index', get_theme_file_uri( '/build/style-index.css' ), null, microtime() );

	wp_localize_script(
		'fictional-university-index',
		'globalObj',
		array(
			'root_url' => get_site_url(),
			'rest_url' => esc_url_raw( rest_url() ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'fictional_university_assets' );

function fictional_university_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'fictional-university-featured-image', 2000, 1200, true );
	add_image_size( 'professor-portrait', 400, 260, true );
	add_image_size( 'professor-landscape', 480, 650, true );
	add_image_size( 'banner-image', 1350, 350, true );

	register_nav_menus(
		array(
			'primary_menu' => __( 'Primary Menu', 'fictional-university' ),
			'footer_one'   => __( 'Footer Menu One', 'fictional-university' ),
			'footer_two'   => __( 'Footer Menu Two', 'fictional-university' ),
		)
	);
}

add_action( 'after_setup_theme', 'fictional_university_setup' );

/**
 * Fictional University Excerpt
 *
 * @return void
 */
function fictional_university_excerpt() {
	if ( has_excerpt() ) {
		echo get_the_excerpt();
	} else {
		echo wp_trim_words( get_the_content(), 130 );
	}
}

function fictional_university_archive_set_query( $query ) {

	if ( ! is_admin() && is_post_type_archive( 'program' ) && $query->is_main_query() ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}

	if ( ! is_admin() && is_post_type_archive( 'event' ) && $query->is_main_query() ) {
		$query->set( 'meta_key', 'event_date' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'ASC' );
		$query->set(
			'meta_query',
			array(
				array(
					'key'     => 'event_date',
					'compare' => '>=',
					'value'   => date( 'Ymd' ),
					'type'    => 'numeric',
				),
			)
		);
	}
}

add_action( 'pre_get_posts', 'fictional_university_archive_set_query' );

/**
 * Fictional University Page Banner
 *
 * @param array $args
 * @return void
 */
function fictional_university_page_banner( $args = null ) {
	// Ensure $args is an array
	$args = (array) $args;

	// Check if 'title' is set, otherwise use the post title
	if ( ! isset( $args['title'] ) || ! $args['title'] ) {
		$args['title'] = get_the_title();
	}

	// Check if 'subtitle' is set, otherwise use the custom field value
	if ( ! isset( $args['subtitle'] ) || ! $args['subtitle'] ) {
		$args['subtitle'] = get_field( 'banenr_subheading' );
	}

	// Check if 'photo' is set or use default image
	if ( ! isset( $args['photo'] ) || ! $args['photo'] ) {
		if ( get_field( 'banner_bg' ) ) {
			$args['photo'] = get_field( 'banner_bg' )['sizes']['banner-image'];
		} else {
			$args['photo'] = get_theme_file_uri( '/images/ocean.jpg' );
		}
	}
	?>
	<div class="page-banner">
		<div class="page-banner__bg-image" style="background-image: url( <?php echo esc_url( $args['photo'] ); ?> )"></div>
		<div class="page-banner__content container container--narrow">
			<h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
			<div class="page-banner__intro">
				<p><?php echo esc_html( $args['subtitle'] ); ?></p>
			</div>
		</div>
	</div>
	<?php
}

// Redirect subscriber to homepage
add_action( 'admin_init', 'fu_redirect_home_for_subscribers' );
function fu_redirect_home_for_subscribers() {
	$current_user = wp_get_current_user();
	if ( count( $current_user->roles ) === 1 && 'subscriber' === $current_user->roles[0] ) {
		wp_redirect( site_url() );
		exit;
	}
}

// Hide admin bar for subscribers
add_action( 'wp_loaded', 'fu_no_admin_bar_for_subscribers' );
function fu_no_admin_bar_for_subscribers() {
	$current_user = wp_get_current_user();
	if ( count( $current_user->roles ) === 1 && 'subscriber' === $current_user->roles[0] ) {
		show_admin_bar( false );
	}
}

// Change WordPress login logo
add_action( 'login_enqueue_scripts', 'fu_custom_login_logo' );
function fu_custom_login_logo() {
	wp_enqueue_style( 'fictional-university-google-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i" rel="stylesheet' );
	wp_enqueue_style( 'fictional-university-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_script( 'fictional-university-index', get_theme_file_uri( '/build/index.js' ), array( 'jquery' ), microtime(), true );
	wp_enqueue_style( 'fictional-university-style', get_stylesheet_uri() );
	wp_enqueue_style( 'fictional-university-index', get_theme_file_uri( '/build/index.css' ), null, microtime() );
	wp_enqueue_style( 'fictional-university-style-index', get_theme_file_uri( '/build/style-index.css' ), null, microtime() );
}

// Change login logo title
add_filter( 'login_headertitle', 'fu_custom_login_logo_title' );
function fu_custom_login_logo_title() {
	return get_bloginfo( 'name' );
}

// Change login logo URL
add_filter( 'login_headerurl', 'fu_custom_login_logo_url' );
function fu_custom_login_logo_url() {
	return esc_url( home_url( '/' ) );
}

// Force to make post status private
add_filter( 'wp_insert_post_data', 'fu_force_private_post_status', 10, 2 );
function fu_force_private_post_status( $data, $postarr ) {
	if ( isset( $data['post_type'] ) && 'note' === $data['post_type'] ) {
		if ( count_user_posts( get_current_user_id(), 'note' ) > 4 && ! $postarr['ID'] ) {
			die( 'You have reached your note limit' );
		}
	}

	if ( isset( $data['post_type'] ) && 'note' === $data['post_type'] ) {
		$data['post_title']   = sanitize_text_field( $data['post_title'] );
		$data['post_content'] = sanitize_textarea_field( $data['post_content'] );
	}

	if ( isset( $data['post_type'] ) && 'note' === $data['post_type'] ) {
		if ( 'trash' !== $data['post_status'] ) {
			$data['post_status'] = 'private';
		}
	}

	return $data;
}
