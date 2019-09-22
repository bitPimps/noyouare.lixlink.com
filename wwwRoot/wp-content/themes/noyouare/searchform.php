<?php
/**
 * Template for displaying search forms in No You Are
 *
 * @package WordPress
 * @subpackage No_You_Are
 * @since No You Are 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'noyouare' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'noyouare' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'noyouare' ); ?>" />
	</form>
