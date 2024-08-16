<ul class="professor-cards">
	<li class="professor-card__list-item">
		<a class="professor-card" href="<?php the_permalink(); ?>">
			<img src="<?php the_post_thumbnail_url( 'professor-portrait' ); ?>" class="professor-card__image" alt="">
			<span class="professor-card__name"><?php the_title(); ?></span>
		</a>
	</li>
</ul>