<?php get_header(); ?>

<div class="page-banner">
	<div class="page-banner__bg-image" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/library-hero.jpg)"></div>
	<div class="page-banner__content container t-center c-white">
	<h1 class="headline headline--large"><?php the_archive_title(); ?></h1>
	<p class="headline headline--small"><?php the_archive_description(); ?></p>
	</div>
</div>

<div class="container container--narrow page-section">
	<?php
	while ( have_posts() ) {
		the_post();
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
	}
	echo paginate_links();

	echo '<p>You can visit the <a href="' . site_url( '/past-events' ) . '">past events page</a> to view all past events.</p>';

	?>
</div>

<?php
get_footer();
