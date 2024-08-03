<?php get_header(); ?>

<div class="page-banner">
	<div class="page-banner__bg-image" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/library-hero.jpg)"></div>
	<div class="page-banner__content container t-center c-white">
	<h1 class="headline headline--large"><?php _e( 'Past Events', 'fictional-university' ); ?></h1>
	</div>
</div>

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
		?>
		<div class="post-item">
			<div class="event-summary">
				<a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
				<?php
					$event_date = get_field( 'event_date' );
					$date       = new DateTime( $event_date );
				?>
				<span class="event-summary__month"><?php echo $date->format( 'M' ); ?></span>
				<span class="event-summary__day"><?php echo $date->format( 'd' ); ?></span>
				</a>
				<div class="event-summary__content">
					<h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
					<p><?php fictional_university_excerpt(); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
				</div>
			</div>
		</div>
		<?php
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
