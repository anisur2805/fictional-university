<?php


// Redirect to home page if user is not logged in.
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/' ) );
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();

	fictional_university_page_banner();
	?>

	<div class="container container--narrow page-section">
		<div class="create-note">
			<h2 class="headline headline--medium">Create New Note</h2>
			<input class="new-note-title" type="text" placeholder="Title">
			<textarea class="new-note-body" placeholder="Your note here..."></textarea>
			<span class="btn btn--blue" id="create-note">Create Note</span>
			<span class="note-limit-message">Note limit reached. Delete an existing note to add a new one.</span>
		</div>

		<ul class="min-list link-list">
			<?php
			$my_notes = new WP_Query(
				array(
					'author'         => get_current_user_id(),
					'post_type'      => 'note',
					'posts_per_page' => -1,
				)
			);
			while ( $my_notes->have_posts() ) {
				$my_notes->the_post();
				?>
				<li class="post-item" data-id="<?php the_ID(); ?>">
					<input readonly type="text" class="note-title-field" value="<?php echo str_replace( 'Private: ', '', esc_attr( get_the_title() ) ); ?>" />
					<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
					<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
					<textarea readonly class="note-body-field"><?php echo esc_attr( get_the_content() ); ?></textarea>
					<span class="update-note btn btn--small btn--blue"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save Note</span>
				</li>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
get_footer();
