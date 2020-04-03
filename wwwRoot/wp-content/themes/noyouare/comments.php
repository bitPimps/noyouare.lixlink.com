<?php
/**
 * Template for displaying Comments
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to noyouare_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage No_You_Are
 * @since No You Are 1.0
 */
?>
	<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'noyouare' ); ?></p>
	</div><!-- #comments -->
		<?php
			/*
			 * Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
			if ( 1 === get_comments_number() ) {
				printf(
					/* translators: %s: The post title. */
					__( 'One thought on &ldquo;%1$s&rdquo;', 'noyouare' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					/* translators: %1$s: The number of comments. %2$s: The post title. */
					_n( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'noyouare' ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'noyouare' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'noyouare' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'noyouare' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
				/*
				 * Loop through and list the comments. Tell wp_list_comments()
				 * to use noyouare_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define noyouare_comment() and that will be used instead.
				 * See noyouare_comment() in noyouare/functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'noyouare_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'noyouare' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'noyouare' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'noyouare' ) ); ?></div>
		</nav>
		<?php endif; // Check for comment navigation. ?>

		<?php
		/*
		 * If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) :
			?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'noyouare' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
