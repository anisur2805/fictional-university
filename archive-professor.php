<?php get_header(); ?>

<div class="page-banner">
	<div class="page-banner__bg-image" style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/library-hero.jpg)"></div>
	<div class="page-banner__content container t-center c-white">
	<h1 class="headline headline--large"><?php the_archive_title(); ?></h1>
	<p class="headline headline--small"><?php the_archive_description(); ?></p>
	</div>
</div>

<div class="container container--narrow page-section">
	<ul>
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php
	}
	echo paginate_links();

	?>
	</ul>
</div>

<?php
get_footer();
