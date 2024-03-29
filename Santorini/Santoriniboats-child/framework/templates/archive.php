<?php defined('ABSPATH') or die('This script cannot be accessed directly.');



/**

 * The template for displaying Archive Pages

 */



$us_layout = US_Layout::instance();



get_header();



?>

<main class="l-main" <?php echo (us_get_option('schema_markup')) ? ' itemprop="mainContentOfPage"' : ''; ?>>

	<?php

	if (us_get_option('enable_sidebar_titlebar', 0)) {



		// Titlebar, if it is enabled in Theme Options

		us_load_template('templates/titlebar');



		// START wrapper for Sidebar

		us_load_template('templates/sidebar', array('place' => 'before'));
	}



	$content_area_id = us_get_page_area_id('content');

	if ($content_area_id != '' and get_post_status($content_area_id) != FALSE) {

		us_load_template('templates/content');
	} else {

	?>

		<section class="l-section<?php echo (us_get_option('row_height') == 'small') ? ' height_small' : ''; ?>">

			<div class="l-section-h i-cf">



				<?php

				do_action('us_before_archive');

				global $us_grid_loop_running;

				$us_grid_loop_running = TRUE;



				// Use Grid element with default values and "Regular" pagination

				us_load_template('templates/us_grid/listing', array('pagination' => 'regular'));



				$us_grid_loop_running = FALSE;

				do_action('us_after_archive');

				?>



			</div>

		</section>

	<?php

	}

	if (us_get_option('enable_sidebar_titlebar', 0)) {

		// AFTER wrapper for Sidebar

		us_load_template('templates/sidebar', array('place' => 'after'));
	}

	?>

</main>



<?php get_footer() ?>