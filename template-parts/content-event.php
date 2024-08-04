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