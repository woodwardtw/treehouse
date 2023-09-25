<?php
/**
 * Partial template for content in timeline.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php
	echo get_the_post_thumbnail( $post->ID, 'full' );	
	?>
	<div class="row">		
		<div class="col-md-8 offset-md-2">
			<?php 
			if ( ! is_page_template( 'page-templates/no-title.php' ) ) {
					the_title(
						'<header class="entry-header"><h1 class="entry-title">',
						'</h1></header><!-- .entry-header -->'
					);
				}
			 ?>
			<div class="entry-content">
				<?php
				the_content();		
				understrap_link_pages();
				?>
			</div><!-- .entry-content -->
		</div>
	</div>
	<div class="timeline-content">
		<?php treehouse_timeline();?>
	</div>

	<footer class="entry-footer">

		<?php understrap_edit_post_link(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
