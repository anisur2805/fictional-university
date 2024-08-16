<?php get_header();

while ( have_posts() ) {
	the_post();

	fictional_university_page_banner();
	?>

<div class="container container--narrow page-section">
	<div class="metabox metabox--position-up metabox--with-home-link">
		<p>
			<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link( 'program' ); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php _e( 'Program', 'fictional-university' ); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
		</p>
	</div>

	<div class="generic-content">
		<?php the_field( 'main_body_content' ); ?>
	</div>

	<?php
	$event_args  = array(
		'post_type'  => 'event',
		'meta_key'   => 'event_date',
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'event_date',
				'compare' => '>=',
				'value'   => date( 'Ymd' ),
				'type'    => 'numeric',
			),
			array(
				'key'     => 'related_program',
				'compare' => 'LIKE',
				'value'   => '"' . get_the_ID() . '"',
			),
		),
	);
	$event_query = new WP_Query( $event_args );
	if ( $event_query->have_posts() ) :
		?>
		<h2 class="headline headline--small-plus">Upcoming <?php echo get_the_title(); ?> Events</h2>
		<?php
		while ( $event_query->have_posts() ) :
			$event_query->the_post();
			get_template_part( 'template-parts/content-event' );
		endwhile;
		wp_reset_postdata();
		endif;
	?>
	<?php
	$program_args  = array(
		'post_type'  => 'professor',
		'orderby'    => 'title',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'related_program',
				'compare' => 'LIKE',
				'value'   => '"' . get_the_ID() . '"',
			),
		),
	);
	$program_query = new WP_Query( $program_args );
	if ( $program_query->have_posts() ) :
		?>
		<hr class="section-break">
		<h2 class="headline headline--small-plus"><?php echo get_the_title(); ?> Professor</h2>
		<ul class="professor-cards">
		<?php
		while ( $program_query->have_posts() ) :
			$program_query->the_post();
			?>
			<li class="professor-card__list-item">
				<a class="professor-card" href="<?php the_permalink(); ?>">
					<img src="<?php the_post_thumbnail_url( 'professor-portrait' ); ?>" class="professor-card__image" alt="">
					<span class="professor-card__name"><?php the_title(); ?></span>
				</a>
			</li>
			<?php
		endwhile;
		wp_reset_postdata();
		endif;
	?>
		</ul>
	</div>
	</div>
	<?php
}

		get_footer();