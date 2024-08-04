<?php get_header();

fictional_university_page_banner(
	array(
		'title'    => get_the_archive_title(),
		'subtitle' => get_the_archive_description(),
	)
);
?>

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
