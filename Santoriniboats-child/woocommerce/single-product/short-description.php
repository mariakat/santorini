<?php

/**
 * Single product short description
 
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

if (!$short_description) {
    return;
}

?>

<!-- Here Starts The plugin  -->
<div class="woocommerce-product-details__short-description">
    <?php echo $short_description; // WPCS: XSS ok. 
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery UI library -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Include datetimepicker CSS and JavaScript files -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/js-datepicker@5.18.0/dist/datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/js-datepicker@5.18.0/dist/datepicker.min.js"></script>

<?php
$post_id = get_the_ID();
$start_date = get_field('start_date', $post_id);
$end_date = get_field('end_date', $post_id);

$product = wc_get_product($product_id);


// Format the dates in PHP to 'YYYY-MM-DD' format
$start_date_formatted = date("d.m.Y", strtotime($start_date));
$end_date_formatted = date("d.m.Y", strtotime($end_date));
?>

<script>
    $(document).ready(function($) {
        var start_Date = "<?php echo $start_date_formatted; ?>"; // Wrap the PHP date in quotes
        var end_Date = "<?php echo $end_date_formatted; ?>"; // Wrap the PHP date in quotes

        // Parse the start and end dates
        var start_DateParts = start_Date.split(".");
        var end_DateParts = end_Date.split(".");

        var startYear = parseInt(start_DateParts[2]);
        var startMonth = parseInt(start_DateParts[1]) - 1; // JavaScript months are 0-based
        var startDay = parseInt(start_DateParts[0]);

        var endYear = parseInt(end_DateParts[2]);
        var endMonth = parseInt(end_DateParts[1]) - 1; // JavaScript months are 0-based
        var endDay = parseInt(end_DateParts[0]);

        // Create an array to store the disabled dates
        var disabledDates = [];

        // Loop through the dates and push them into the array
        var currentDate = new Date(startYear, startMonth, startDay);
        var end_DateObj = new Date(endYear, endMonth, endDay);

        while (currentDate < end_DateObj) {
            var formattedDate = ("0" + (currentDate.getDate())).slice(-2) + "." + ("0" + (currentDate.getMonth() + 1)).slice(-2) + "." + currentDate.getFullYear();
            disabledDates.push(formattedDate);
            currentDate.setDate(currentDate.getDate() + 1);
        }

        // Print the disabled dates to the console or use them as needed
        console.log(disabledDates);

        $("#date-1635118336615").datetimepicker({
            "formatDate": "d.m.Y",
            "beforeShowDay": function(date) {
                var formattedDate = ("0" + (date.getDate())).slice(-2) + "." + ("0" + (date.getMonth() + 1)).slice(-2) + "." + date.getFullYear();
                if (disabledDates.indexOf(formattedDate) !== -1) {
                    return [false];
                }
                return [true];
            }
        });
    });
</script>

