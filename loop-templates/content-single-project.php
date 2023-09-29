<?php
/**
 * Partial template for content in single-project.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

<?php
	echo get_the_post_thumbnail( $post->ID, 'full', array('class'=>'img-fluid aligncenter') );	
	?>
	<div class="row title-row">		
		<div class="col-md-6 offset-md-3">
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
	<?php get_template_part( 'loop-templates/content', 'flexcontent' );?>

	<footer class="entry-footer">

		<?php understrap_edit_post_link(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
