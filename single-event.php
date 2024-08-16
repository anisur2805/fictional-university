<?php get_header();

while ( have_posts() ) {
	the_post();

	fictional_university_page_banner();
	?>

<div class="container container--narrow page-section">
	<div class="metabox metabox--position-up metabox--with-home-link">
		<p>
			<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link( 'event' ); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php _e( 'Event', 'fictional-university' ); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
		</p>
	</div>

	<div class="generic-content">
		<?php the_field( 'main_body_content'); ?>
	</div>

	<?php
	$related_programs = get_field( 'related_program' );
	if ( $related_programs ) {
		echo '<hr class="section-break">';
		echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
		echo '<ul class="link-list min-list">';

		foreach ( $related_programs as $program ) {
			?>
			<li><a href="<?php echo get_the_permalink( $program ); ?>"><?php echo get_the_title( $program ); ?></li>
			<?php
		}
	}
	?>
</div>
	<?php
}

get_footer();