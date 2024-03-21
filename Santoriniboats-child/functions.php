<?php

/* Custom functions code goes here. */

function my_theme_scripts()
{

    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.4.min.js', array('jquery'), '3.6.4', true);

    wp_enqueue_script('jquery-migrate', 'https://code.jquery.com/jquery-migrate-1.4.1.min.js', array('jquery'), '3.6.4', true);
}

add_action('wp_enqueue_scripts', 'my_theme_scripts');

add_action('wp_ajax_nopriv_save_event_data', 'save_event_data_callback');
add_action('wp_ajax_save_event_data', 'save_event_data_callback');

function save_event_data_callback()
{
    // Your AJAX handling logic here
    // Verify nonce

    // Retrieve data sent via AJAX
    $json_data = urldecode($_POST['data']);
    $data = json_decode($json_data, true);
    $post_id =  $data['productId'];

    error_log('error_log: ' . $post_id);
    // Update post meta with a specific key
    update_post_meta($post_id, 'start_date', $data['start']);
    update_post_meta($post_id, 'end_date', $data['end']);
    update_post_meta($post_id, 'event_id', $data['id']);
    // Process the data (you can save it to a file, database, etc.)
    // For example, saving to a file:
    // $file_path = get_stylesheet_directory() . '/page-templates/events.php';
    $file_path_json = get_stylesheet_directory() . '/page-templates/events_data.json';


    // Load existing data from the JSON file if it exists
    $existing_data = file_exists($file_path_json) ? json_decode(file_get_contents($file_path_json), true) : [];

    // Add the new data to the array
    $existing_data[] = $data;

    // Save the updated array as a JSON file
    file_put_contents($file_path_json, json_encode($existing_data));
    error_log(print_r($existing_data, true));

    // Save some test data to another file
    // file_put_contents($file_path, json_encode($existing_data));

    // Send a response (optional)
    wp_send_json_success('Data saved successfully!');
    // Always exit to avoid extra output
    wp_die();
}



add_action('wp_ajax_nopriv_delete_event_data', 'delete_event_data_callback');
add_action('wp_ajax_delete_event_data', 'delete_event_data_callback');

function delete_event_data_callback()
{
    // Verify nonce

    // Retrieve data sent via AJAX
    $json_data = urldecode($_POST['data']);
    $data = json_decode($json_data, true);
    $post_id = $data['productId']; // Assuming productId contains the post ID
   

    

    // Delete post meta
    delete_post_meta($post_id, 'start_date');
    delete_post_meta($post_id, 'end_date');
    delete_post_meta($post_id, 'event_id');

    // Load existing data from the JSON file
        $file_path_json = get_stylesheet_directory() . '/page-templates/events_data.json';
        $existing_data = file_exists($file_path_json) ? json_decode(file_get_contents($file_path_json), true) : [];
        // error_log(print_r($existing_data, true));

        

    

        
    // Remove the deleted data from the array
    $updated_data = array_filter($existing_data, function ($json_data) use ($data) {
         $event_id = $event['id'];
         $data_id = $data['id'];


        return $event_id !== $data_id;
    });
    file_put_contents($file_path_json, json_encode($updated_data));
 
    // Save the updated array as a JSON file
    

    // error_log('Received data: ' . print_r($data, true));
    // error_log('Event ID to delete: ' . $event['id']);
    // Send a response (optional)
    wp_send_json_success('Data deleted successfully!');

    // Always exit to avoid extra output
    wp_die();
}




function localize_ajax_url()
{
    echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}
add_action('wp_head', 'localize_ajax_url');




// function my_theme_admin_scripts()

// {

//     wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.4.min.js', array('jquery'), '3.6.4', true);

//     wp_enqueue_script('jquery-migrate', 'https://code.jquery.com/jquery-migrate-1.4.1.min.js', array('jquery'), '3.6.4', true);

// }

// add_action('admin_enqueue_scripts', 'my_theme_admin_scripts');



add_filter('woocommerce_should_load_paypal_standard', '__return_true');

require_once get_stylesheet_directory() . '/travel/travel-functions.php';



if (!function_exists('minimalist_writer_sidebars_register')) :

    function minimalist_writer_sidebars_register()
    {



        register_sidebar(

            array(

                'name' => esc_html__('Eshop Sidebar', 'caramelx'),

                'id' => 'eshop-sidebar',

                'description' => esc_html__('Add widgets here.', 'caramelx'),

                'before_widget' => '<section id="%1$s" class="widget %2$s mb-45">',

                'after_widget' => '</section>',

                'before_title' => '<h3 class="widget-title  mb-25">',

                'after_title' => '</h3>',

            )

        );
    }



    add_action('widgets_init', 'minimalist_writer_sidebars_register');

endif;





function enable_readmore()
{

?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {



            $('.readMore').on('click', function() {

                $('.packageReadMore').slideToggle();



                ($(this).text() === "Read More") ? $(this).text("Show Less"): $(this).text("Read More");

                return false;

            });

        });
    </script>

<?php

}



add_action('wp_footer', 'enable_readmore');



function woocommerce_single_product_summary()
{

    $productid = get_queried_object_id();

    $productOBJ = wc_get_product($productid);

    $opts = get_option('santorin_options');







    //$html .= '<h4 class="from">' . $productOBJ->get_price() . '</h4>';

    $discounts = $opts['discount-group'];



    $html = '<div class="cruise-discounts">';

    if (!empty($discounts)) :

        foreach ($discounts as $d) {



            if ($d['cruise-select'] == $productid) {

                $discount = $d['cruise-discount-number'];

                $discountPrice = ($productOBJ->get_price()) * (1 - ($discount / 100));

                $discountDays = $d['cruise-discount-dates'];

                $html .= '<div class="from-date">From ' . $discountDays['from'] . ' to ' . $discountDays['to'] . ': <strong>' . $discountPrice . '</strong> €</div>';
            }
        }

    endif;

    $html .= '</div>';



    echo $html;
}



add_action('woocommerce_single_product_summary', 'woocommerce_single_product_summary', 11);



if (!function_exists('awcdp_build_payment_schedule')) {

    function awcdp_build_payment_schedule($remaining_amounts, $deposit, $cart_items_deposit_amount)
    {

        // Modify the function implementation or add your custom code here to stop the function

        // Return the desired value or do nothing

        return array();
    }
}





/**

 * @snippet       Add CSS to WooCommerce Emails

 * @how-to        Get CustomizeWoo.com FREE

 * @author        Rodolfo Melogli

 * @compatible    Woo 4.6

 * @donate $9     https://businessbloomer.com/bloomer-armada/

 */



add_filter('woocommerce_email_styles', 'bbloomer_add_css_to_emails', 9999, 2);



function bbloomer_add_css_to_emails($css, $email)
{

    $css .= '

.items div{;margin:10px 0;}

.items div:last-child{display:none!important;}

.wc-item-meta-label{font-weight:400!important;}

.wc-item-meta li:last-child{display:none!important}';

    return $css;
}





require_once('page-templates/cruises_settings.php');




