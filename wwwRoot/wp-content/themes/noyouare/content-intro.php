<?php
/**
 * Template for displaying page content in the showcase.php page template
 *
 * @package WordPress
 * @subpackage No_You_Are
 * @since No You Are 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'intro' ); ?>>
	<header class="entry-header">
		<h2 class="entry-title"><?php the_title(); ?></h2>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-link"><span>' . __( 'Pages:', 'noyouare' ) . '</span>',
				'after'  => '</div>',
			)
		);
		?>
		<?php edit_post_link( __( 'Edit', 'noyouare' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
