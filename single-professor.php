<?php get_header();

while ( have_posts() ) {
	the_post();

	fictional_university_page_banner();
	?>

<div class="container container--narrow page-section">
	<div class="metabox metabox--position-up metabox--with-home-link">
		<p>
			<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link( 'professor' ); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php _e( 'Progessor', 'fictional-university' ); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
		</p>
	</div>

	<div class="generic-content">
		<div class="row group">
			<div class="one-third">
				<img src="<?php the_post_thumbnail_url( 'professor-portrait' ); ?>" alt="">
			</div>
			<div class="two-thirds">
				<?php the_field( 'main_body_content' ); ?>
			</div>
		</div>
	</div>

		<?php
		$event_args  = array(
			'post_type'  => 'professor',
			'orderby'    => 'title',
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
				?>
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
					<?php
			endwhile;
			wp_reset_postdata();
			endif;
		?>
		</div>
		<?php
}

		get_footer();