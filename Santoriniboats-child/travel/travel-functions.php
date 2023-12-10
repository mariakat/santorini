<?php
require_once get_stylesheet_directory() . '/travel/mySqli/MysqliDb.php';
function travel_add_js()
{
    wp_enqueue_script('travel', get_stylesheet_directory_uri() . '/travel/assets/travel-js.js', array('jquery'), time(), true);
    wp_localize_script('travel', 'BookObj', array(
        'ajaxURL' => admin_url('admin-ajax.php'),
        'currentID' => get_queried_object_id(),
        'isProduct' => is_product()
    ));
}

add_action('wp_enqueue_scripts', 'travel_add_js');
if (!function_exists('travel_connect_to_db')) :
    function travel_connect_to_db()
    {
        $db = new MysqliDb('localhost', 'santorin_db', '6o+2TT0~]LcI', 'santorin_db');
        return $db;
    }
endif;
function santorini_checkout($fields)
{

    unset($fields['billing']['billing_company']);
    //unset($fields['billing']['billing_city']);
    //unset($fields['billing']['billing_address_1']);
    //unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    //unset($fields['billing']['billing_postcode']);


    return $fields;
}

add_filter('woocommerce_checkout_fields', 'santorini_checkout');

add_action('woocommerce_before_order_notes', 'santorini_drivers_license_checkout');

function santorini_drivers_license_checkout($checkout)
{

    echo '<div id="drivers-license" class="form-row  validate-required" style="margin-bottom: -30px;">';

    woocommerce_form_field('driver_license', array(
        'type' => 'text',
        'class' => array('checkbox'),
        'label' => __('Driver License.'),
        'required' => false,
    ));

    echo '</div>';
}


add_action('woocommerce_checkout_process', 'santorini_checkout_process');

function santorini_checkout_process()
{
    global $woocommerce;


    if (!(int)isset($_POST['driver_license'])) {
        wc_add_notice(__('<strong>Drivers </strong> License is required'), 'error');
    }
}

/**
 * Add the field to the checkout
 */
add_action('woocommerce_after_checkout_billing_form', 'my_custom_checkout_field');

function my_custom_checkout_field($checkout)
{

    echo '<div id="my_custom_checkout_field"><h2>' . __('Identification Type') . '</h2>';

    woocommerce_form_field('my_field_name', array(
        'type' => 'text',
        'class' => array('my-field-class form-row-wide'),
        'label' => __('Identity  or  Passport '),
        'required' => true,
        'placeholder' => __('Enter your Identity  or  Passport Number'),
    ), $checkout->get_value('my_field_name'));

    echo '</div>';
}


/**
 * Update the order meta with field value
 */
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

function my_custom_checkout_field_update_order_meta($order_id)
{
    if (!empty($_POST['my_field_name'])) {
        update_post_meta($order_id, 'Identification Type', sanitize_text_field($_POST['my_field_name']));
    }
}


/**
 * Display field value on the order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1);

function my_custom_checkout_field_display_admin_order_meta($order)
{
    echo '<p><strong>' . __('Identification Type') . ':</strong> ' . get_post_meta($order->id, 'Identification Type', true) . '</p>';
}


/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process()
{
    // Check if set, if its not set add an error.
    if (!$_POST['my_field_name'])
        wc_add_notice(__('Identification Type is a required field.'), 'error');
}


/**
 * Add a custom field (in an order) to the emails
 */
add_filter('woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3);

function custom_woocommerce_email_order_meta_fields($fields, $sent_to_admin, $order)
{
    $fields['Identification Type'] = array(
        'label' => __('Identification Type'),
        'value' => get_post_meta($order->id, 'Identification Type', true),
    );
    $fields['Do you have a car on Santorini?'] = array(
        'label' => __('Do you have a car on Santorini?'),
        'value' => get_post_meta($order->id, 'Do you have a car on Santorini?', true),
    );
    $fields['Date of Birth'] = array(
        'label' => __('Date of Birth'),
        'value' => get_post_meta($order->id, 'Date of Birth', true),
    );
    $fields['Phone'] = array(
        'label' => __('Phone'),
        'value' => get_post_meta($order->id, 'Phone', true),
    );
    return $fields;
}


//* Add select field to the checkout page

add_action('woocommerce_before_order_notes', 'njengah_add_select_checkout_field');

function njengah_add_select_checkout_field($checkout)
{

    echo '<h2>' . __('Do you have a car on Santorini?') . '</h2>';

    woocommerce_form_field(
        'daypart',
        array(

            'type' => 'select',

            'class' => array('njengah-drop'),


            'label' => __('If not we can help you find the best offers from our partners network'),

            'required' => true,

            'options' => array(

                'blank' => __('Select', 'njengah'),

                'No, I dont have any' => __('No, I dont have any', 'njengah'),

                'Yes I have rented a car/bike' => __('Yes I have rented a car/bike', 'njengah'),

                'I would like to get an offer' => __(' I would like to get an offer', 'njengah')

            )

        ),

        $checkout->get_value('daypart')
    );
}


/**
 * Update the order meta with field value
 */
add_action('woocommerce_checkout_update_order_meta', 'njengah_add_select_checkout_field_update_order_meta');

function njengah_add_select_checkout_field_update_order_meta($order_id)
{
    if (!empty($_POST['daypart'])) {
        update_post_meta($order_id, 'Do you have a car on Santorini?', sanitize_text_field($_POST['daypart']));
    }
}


/**
 * Display field value on the order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address', 'njengah_add_select_checkout_field_display_admin_order_meta', 10, 1);

function njengah_add_select_checkout_field_display_admin_order_meta($order)
{
    echo '<p><strong>' . __('Do you have a car on Santorini?') . ':</strong> ' . get_post_meta($order->id, 'Do you have a car on Santorini?', true) . '</p>';
}


/**
 * Add custom field to the checkout page
 */

add_action('woocommerce_after_checkout_billing_form', 'custom_checkout_field');

function custom_checkout_field($checkout)
{


    echo '<div id="date-of-birth-field"><h2>' . __('Date of Birth') . '</h2>';


    woocommerce_form_field('date_of_birth', array(
        'type' => 'date',
        'class' => array('my-dateofbirth-class form-row-wide'),
        'label' => __('Minimum Limit 21 Years'),
        'required' => true,
        'placeholder' => __('DD/MM'),
    ), $checkout->get_value('date_of_birth'));


    echo '</div>';
}


/**
 * Update the order meta with field value
 */
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta($order_id)
{
    if (!empty($_POST['date_of_birth'])) {
        update_post_meta($order_id, 'Date of Birth', sanitize_text_field($_POST['date_of_birth']));
    }
}


/**
 * Display field value on the order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address', 'custom_checkout_field_display_admin_order_meta', 10, 1);

function custom_checkout_field_display_admin_order_meta($order)
{
    echo '<p><strong>' . __('Date of Birth') . ':</strong> ' . get_post_meta($order->id, 'Date of Birth', true) . '</p>';
}


function zpd_replace_wc_add_to_cart_button()
{
    global $product;
    // This adds some URL query variables that may be useful to input into a contact form - remove if not needed
    $product_link_params = sprintf(
        '?wc_id=%s&wc_price=%s&wc_title=%s&wc_product_link=%s',
        $product->get_id(),
        $product->get_display_price(),
        $product->get_title(),
        $product->get_permalink()
    );
    $button_text = 'Learn More & Book';
    $link = $product->get_permalink();

    if ($product->get_id() == 1605) {
        echo '<p class="zpd-wc-reserve-item-button">';
        echo do_shortcode('<a  href="https://santoriniboatrental.gr/contact-us" target="_blank" class="button addtocartbutton">' . $button_text . '</a>');
        echo '</p>';
    } else {
        echo '<p class="zpd-wc-reserve-item-button">';
        echo do_shortcode('<a  href="' . $link . '" class="button addtocartbutton">' . $button_text . '</a>');
        echo '</p>';
    }
}

add_action('woocommerce_after_shop_loop_item', 'zpd_replace_wc_add_to_cart_button');
//add_action( 'woocommerce_single_product_summary','zpd_replace_wc_add_to_cart_button' );


/**
 * Checkout Process
 */
add_action('woocommerce_checkout_process', 'customised_checkout_field_process');
function customised_checkout_field_process()
{


    // Show an error message if the field is not set.


    if (!$_POST['date_of_birth']) wc_add_notice(__('Please enter date of birth!'), 'error');
}


/**
 * Save the value given in custom field
 *
 * add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');
 * function custom_checkout_field_update_order_meta($order_id){
 *
 *
 *
 * if (!empty($_POST['date_of_birth'])) {
 * //convert the submitted date so that we get only MM/DD as the Mailchimp set date format
 * $date = new DateTime($_POST['date_of_birth']);
 * $birthday = $date->format('d/m');
 *
 *
 *
 * update_post_meta($order_id, 'Date of Birth', $birthday);
 *
 *
 *
 * }
 *
 *
 *
 * }
 */


add_filter('woocommerce_loop_add_to_cart_link', 'replace_loop_add_to_cart_button', 10, 2);
function replace_loop_add_to_cart_button($button, $product)
{
    // Not needed for variable products
    if ($product->is_type('variable')) return $button;

    // Button text here
    $button_text = __("Read More", "woocommerce");

    return '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
}

add_action('woocommerce_checkout_update_order_meta', 'bt_checkout_field_order_meta_db');
/**
 * Add custom field as order meta with field value to database
 */
function bt_checkout_field_order_meta_db($order_id)
{
    if (!empty($_POST['drivers_license'])) {
        update_post_meta($order_id, 'drivers_license', sanitize_text_field($_POST['drivers_license']));
    }
    if (!empty($_POST['birthdate'])) {
        update_post_meta($order_id, 'birthdate', sanitize_text_field($_POST['birthdate']));
    }
    if (!empty($_POST['Phone'])) {
        update_post_meta($order_id, 'Phone', sanitize_text_field($_POST['Phone']));
    }
}

add_filter('woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text');
function woocommerce_custom_single_add_to_cart_text()
{
    return __('BOOK NOW', 'woocommerce');
}

add_filter('woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text');
function custom_woocommerce_product_add_to_cart_text()
{
    global $product;
    return __('BOOK NOW', 'woocommerce');
}

if (!function_exists('santorini_save_booking')) :
    function santorini_save_booking($order_id)
    {
        if (!$order_id)
            return;

        // Get an instance of the WC_Order object
        $order = wc_get_order($order_id);
        // Get the order key
        $order_key = $order->get_order_key();
        // Get the order number
        $order_key = $order->get_order_number();
        // Get the order email
        $order_email = $order->get_billing_email();

        foreach ($order->get_items() as $item_id => $item) {

            // Get the product object
            $product = $item->get_product();

            // Get the product Id
            $product_id = $product->get_id();

            // Get the product name
            //$product_id = $item->get_name();


            $datePick = $item->get_meta('Pick a date!', true);
            $createDate = date_create($datePick);
            $data = array(
                'order_id' => $order_id,
                'cruiseID' => $product_id,
                'personEmail' => $order_email,
                'cruiseDate' => date_format($createDate, 'Y/m/d H:i:s')
            );
            $con = travel_connect_to_db();


            $res = $con->insert('wpuq_santorini_bookings', $data);
            if ($res) {
                echo 'Booking saved.';
            }
            if ($con->getLastErrno() === 0)
                echo 'Update succesfull';
            else
                echo 'Update failed. Error: ' . $con->getLastError();
        }
    }


endif;

if (!function_exists('santorini_save_booking_new')) :
    function santorini_save_booking_new($order_id)
    {
        if (!$order_id)
            return;

        // Get an instance of the WC_Order object
        $order = wc_get_order($order_id);
        // Get the order key
        $order_key = $order->get_order_key();
        // Get the order number
        $order_key = $order->get_order_number();
        // Get the order email
        $order_email = $order->get_billing_email();

        foreach ($order->get_items() as $item_id => $item) {

            // Get the product object
            $product = $item->get_product();

            // Get the product Id
            $product_id = $product->get_id();

            // Get the product name
            //$product_id = $item->get_name();


            $datePick = $item->get_meta('Pick a date!', true);
            $createDate = date_create($datePick);
            $boat = $item->get_meta('Boat', true);

            $data = array(
                'order_id' => $order_id,
                'cruiseID' => $product_id,
                'personEmail' => $order_email,
                'cruiseDate' => date_format($createDate, 'Y/m/d H:i:s'),
                'boat_number' => $boat
            );
            $con = travel_connect_to_db();


            $res = $con->insert('wpuq_santorini_new_bookings', $data);
            if ($res) {
                echo 'Booking saved.';
            }
            if ($con->getLastErrno() === 0)
                echo 'Update succesfull';
            else
                echo 'Update failed. Error: ' . $con->getLastError();
        }
    }


endif;
add_action('woocommerce_thankyou', 'santorini_save_booking_new');

/** Check ajax and validate booking **/
function santorini_validate_booking()
{
    $cruiseID = $_POST['cruiseID'];
    $date = $_POST['date'];
    $fDate = date_create($date);
    $bookingDateTime = date_format($fDate, 'Y/m/d H:i:s');

    /** BLOCKED DAYS */
    $blockOptions = get_option('santorin_options');
    $blockedDays = $blockOptions['cruises-group'];
    $blockDates = array();
    if (!empty($blockedDays)) :
        foreach ($blockedDays as $c) {

            if ($c['cruise-select'] == $cruiseID) {
                // found the blocked
                $cr = $c['cruise-group'];
                foreach ($cr as $day) {
                    $ff = date_create($day['cruise-date']);
                    $bookingBlockDate = date_format($ff, 'Y/m/d 00:00:00');
                    $blockDates[] = $bookingBlockDate;
                }
            }
        }
    endif;

    /** BLOCKED DAYS */

    if (in_array($bookingDateTime, $blockDates)) {
        $alreadyBooked = true;
    } else {


        $book = travel_connect_to_db();
        $book->where('cruiseDate', $bookingDateTime);
        $book->where('cruiseID', $cruiseID);
        $res = $book->getOne('wpuq_santorini_bookings', 'cruiseDate');
        if ($book->count > 4) {
            $alreadyBooked = true;
        } else {
            $alreadyBooked = false;
        }
    }
    echo json_encode(array(
        'rawdate' => $date,
        'date' => $bookingDateTime,
        'cruiseID' => $cruiseID,
        'alreadyBooked' => $alreadyBooked,
        'count' => $book->count,
        'cruises_group' => $blockDates,
        'success' => 1
    ));
    die();
}

add_action('wp_ajax_santorini_validate_booking', 'santorini_validate_booking');
add_action('wp_ajax_nopriv_santorini_validate_booking', 'santorini_validate_booking');


/*
 * Options :)
 */

// Check core class for avoid errors
if (class_exists('CSF')) {

    // Set a unique slug-like ID
    $prefix = 'santorin_options';

    // Create options
    CSF::createOptions($prefix, array(
        'menu_title' => 'Booking Options',
        'menu_slug' => 'santorini-booking-opts',
    ));

    // Create a section
    CSF::createSection($prefix, array(
        'title' => 'Disable Dates',
        'fields' =>
        array(
            array(
                'id' => 'cruises-group',
                'type' => 'group',
                'title' => 'Cruise',
                'fields' => array(
                    array(
                        'id' => 'cruise-select',
                        'type' => 'select',
                        'title' => 'Select the cruise',
                        'placeholder' => 'Please select..',
                        'options' => 'posts',
                        'query_args' => array(
                            'post_type' => 'product',
                        ),
                    ),
                    array(
                        'id' => 'cruise-group',
                        'type' => 'group',
                        'title' => 'Blocked Days',
                        'fields' => array(
                            array(
                                'id' => 'cruise-date',
                                'type' => 'date',
                                'title' => 'Blocked Day',
                                'settings' => array(
                                    'dateFormat' => 'MM dd, yy'
                                ),

                            ),
                        ),
                    ),

                ),

            )
        )
    ));


    CSF::createSection($prefix, array(
        'title' => 'Cruise Discounts',
        'fields' => array(

            array(
                'id' => 'discount-group',
                'type' => 'group',
                'title' => 'Discount',
                'fields' => array(
                    array(
                        'id' => 'cruise-select',
                        'type' => 'select',
                        'title' => 'Select the cruise',
                        'placeholder' => 'Please select..',
                        'options' => 'posts',
                        'query_args' => array(
                            'post_type' => 'product',
                        ),
                    ),
                    array(
                        'id' => 'cruise-discount-number',
                        'type' => 'number',
                        'title' => 'Discount (percent)',
                    ),
                    array(
                        'id' => 'cruise-discount-dates',
                        'type' => 'date',
                        'title' => 'Date From - To',
                        'subtitle' => 'Date with "From" and "To"',
                        'from_to' => true,
                        'settings' => array(
                            'dateFormat' => 'MM dd, yy'
                        ),
                    ),

                ),

            )


        )
    ));
}


//function new_booking_validate_booking($bookingDateTime, $cruiseID = '')
//{
//    /**
//     *  Boats 4
//     *  Weights Max 4
//     * @bool alreadyBooked (This should be at the end true or false)
//     * @desc $cruiseID = $_POST['cruiseID'];
//     * $date = $_POST['date'];
//     * $fDate = date_create($date);
//     * $bookingDateTime = date_format($fDate, 'Y/m/d H:i:s');
//     */
//
//    $availableBoats = array(1, 2, 3, 4);
//    $alreadyBooked = false;
//    $book = travel_connect_to_db();
//    $book->where('cruiseDate', $bookingDateTime);
//    $res = $book->get('wpuq_santorini_new_bookings'); // Here gets all the booking within the same day.
//
//    /* Count all the weights and add them */
//    if (!empty($res)):
//
//        $bookingMessage = 'This is the final message';
//        $sum_of_weights = 0;
//        foreach ($res as $r) {
//            $sum_of_weights += $r['cruise_weight'];
//
//        }
//
//        if ($sum_of_weights > 4) {
//            $alreadyBooked = 1;
//
//            if ($cruiseID == 1555) {
//                //Suggest another date within 3 days each
//
//                //$cRes = $book->rawQuery('SELECT SUM(cruise_weight) as allWeights FROM wpuq_santorini_new_bookings WHERE cruiseDate = "'.$bookingDateTime.'"');
//                for ($i = 1; $i <= 7; $i++) {
//                    $cRes = $book->rawQuery('SELECT SUM(cruise_weight) as allWeights FROM wpuq_santorini_new_bookings WHERE cruiseDate = DATE_ADD("' . $bookingDateTime . '",INTERVAL + "' . $i . '" DAY)');
//                    foreach ($cRes as $cr) {
//                        if ($cr['allWeights'] < 4) {
//                            echo "Available at:" . date('d/m/Y', strtotime($bookingDateTime . ' + ' . $i . ' days'));
//                        }
//                    }
//                }
//            } else if ($cruiseID == 1558) {
//                //Suggest another date within 1 week
//            }
//            $bookingMessage = 'All cruises are fully booked. Please search another date.';
//        } else {
//
//            //Here is the main part.We have to search other stuff like type of cruise etc
//            $alreadyBooked = 0;
//            if ($sum_of_weights < 4) {
//                $boats = array(1, 2, 3, 4);
//                $fullyBooked = array();
//                $availableBoats = array();
//                //should check availability of boats 1,2,3,4
//                //check if any boat is assigned full day.
//
//                //Full day assigned boats
//                $book->where('cruiseDate', $bookingDateTime);
//                $book->where('cruiseID', 1558);
//                $fullyBookedRes = $book->get('wpuq_santorini_new_bookings'); // Here gets all the booking within the same day.
//
//                if (!empty($fullyBookedRes)):
//                    foreach ($fullyBookedRes as $bb) {
//                        $fullyBooked[] = $bb['boat_number'];
//                    }
//                endif;
//
//                $availableBoats = array_diff($boats, $fullyBooked);
//
//                $book->where('cruiseDate', $bookingDateTime);
//                $book->where('cruiseID', $cruiseID);
//                $book->where('boat_number', $availableBoats, 'IN');
//                $theBoatRes = $book->get('wpuq_santorini_new_bookings');
//                if (!empty($theBoatRes)):
//                    echo 'Available Boats atm: ';
//                    print_r($availableBoats);
//                    foreach ($theBoatRes as $tb) {
//                        if ($tb['cruiseID'] == $cruiseID) {
//                            foreach (array_keys($availableBoats, $tb['boat_number'], true) as $key) {
//                                unset($availableBoats[$key]);
//                            }
//                            echo $firstAvailable = $availableBoats[array_rand($availableBoats)];
//                            echo 'here 1';
//                        }
//                        //echo 'Boat'.  $tb['boat_number'];
//                    }
//                else:
//                    echo 'here2';
//                    $freeBoats = array(1, 2, 3, 4);
//                    $assignedBoats = array();
//                    $book->where('cruiseDate', $bookingDateTime);
//                    $theBoatRes = $book->get('wpuq_santorini_new_bookings');
//
//                    foreach ($theBoatRes as $bb) {
//                        $assignedBoats[] = $bb['boat_number'];
//                    }
//                    $lastFree = array_diff($freeBoats, $assignedBoats);
//                    echo 'the free boats for the specific day are..:';
//                    echo print_r($lastFree);
//
//                    if ($cruiseID == 1558 && empty($lastFree)): // full day
//                        $alreadyBooked = 1;
//                        $bookingMessage = 'The cruised cannot be booked.';
//                    endif;
//
//                endif;
//
//                $data = array(
//                    'order_id' => 554454,
//                    'cruiseID' => $cruiseID,
//                    'cruise_weight' => new_booking_cruise_weights($cruiseID),
//                    'personEmail' => 'amertzanos@seogreece.gr',
//                    /*'cruiseDate' => '2023-01-10 00:00:00',*/
//                    'cruiseDate' => $bookingDateTime,
//                    'boat_number' => $firstAvailable
//                );
//
//                $res = $book->insert('wpuq_santorini_new_bookings', $data);
//                $bookingMessage = 'The cruised is booked!';
//            }
//
//            echo '<pre>';
//            echo '===================';
//            echo '<br/>';
//            print_r('This day the sum of weights is: ' . $sum_of_weights);
//            echo '<br/>';
//            print_r($fullyBooked);
//            echo '<br/>';
//            print_r($availableBoats);
//            echo '====================';
//            echo '</pre>';
//
//        }
//
//    else:
//        //The first booking for the day.
//        $firstAvailable = $availableBoats[array_rand($availableBoats)];
//        $data = array(
//            'order_id' => 554454,
//            'cruiseID' => $cruiseID,
//            'cruise_weight' => new_booking_cruise_weights($cruiseID),
//            'personEmail' => 'amertzanos@seogreece.gr',
//            'cruiseDate' => '2023-01-10 00:00:00',
//            //'cruiseDate' => $bookingDateTime,
//            'boat_number' => $firstAvailable
//        );
//
//        $res = $book->insert('wpuq_santorini_new_bookings', $data);
//    endif;
//
//
//    $bookingInfo = array();
//
//    $bookingInfo['alreadyBooked'] = $alreadyBooked;
//    $bookingInfo['bookingMessage'] = $bookingMessage;
//    $bookingInfo['cruise_weights'] = $sum_of_weights;
//
//    return $bookingInfo;
//
//}

function santorini_last_booking()
{
    $altMessage = '';
    $bookingShips = array('1', '2', '3', '4', '5');
    $fulldayBookingId = 1558;
    $bookingInfo = array();


    $cruiseID = $_POST['cruiseID'];
    $date = $_POST['date'];
    $fDate = date_create($date);
    $bookingDateTime = date_format($fDate, 'Y/m/d H:i:s');

    /** POSSIBLE DISCOUNTS **/
    $opts = get_option('santorin_options');
    $discounts = $opts['discount-group'];
    $discountVal = 1;
    $dateforDiscount = $bookingDateTime;

    if (!empty($discounts)) :
        foreach ($discounts as $d) {

            if ($d['cruise-select'] == $cruiseID) {
                $discountVal = $d['cruise-discount-number'];
                $discountDays = $d['cruise-discount-dates'];

                $from = date_create($discountDays['from']);
                $bookingFrom = date_format($from, 'Y/m/d 00:00:00');

                $to = date_create($discountDays['to']);
                $bookingTo = date_format($to, 'Y/m/d 00:00:00');

                if (strtotime($dateforDiscount) >= strtotime($bookingFrom) && strtotime($dateforDiscount) <= strtotime($bookingTo)) {
                    $dis = number_format(floatval($discountVal / 100), 2);
                    break;
                } else {
                    $dis = 1;
                }


                //               echo $bookingFrom;
                //               echo $bookingTo;
            }
        }
    endif;


    /** BLOCKED DAYS */
    $blockOptions = get_option('santorin_options');
    $blockedDays = $blockOptions['cruises-group'];
    $blockDates = array();
    if (!empty($blockedDays)) :
        foreach ($blockedDays as $c) {

            if ($c['cruise-select'] == $cruiseID) {
                // found the blocked
                $cr = $c['cruise-group'];
                foreach ($cr as $day) {
                    $ff = date_create($day['cruise-date']);
                    $bookingBlockDate = date_format($ff, 'Y/m/d 00:00:00');
                    $blockDates[] = $bookingBlockDate;
                }
            }
        }
    endif;
    if (in_array($bookingDateTime, $blockDates)) {
        $alreadyBooked = true;
    } else {
        $book = travel_connect_to_db(); //connect..
        /**
         * 1. Εχουμε 4 πλοία. Βρίσκουμε ποιο/ποια ειναι fully booked τη συγκεκριμένη μέρα. Για του λογου το αληθές
         * το Full Day Cruise ειναι το 1558.
         */
        $book->where('cruiseDate', $bookingDateTime);
        $book->where('cruiseID', $fulldayBookingId);
        $results = $book->get('wpuq_santorini_new_bookings');

        if (!empty($results)) {
            foreach ($results as $r) {
                unset($bookingShips[$r['boat_number'] - 1]);
            }
        }
        /**
         * 2. Εδώ πλέον και αφου ξεκαθάρισα ποια ειναι fully booked πρεπει να βρω ποια ειναι partially
         * booked γιατι εδώ δεν μπορώ να εχω και partially + ίδιο cruise ID.
         * Οταν εχω επιλέξει παλι FULL (1558) εκει πρέπει να κόβω πληρως το partially
         */
        $book->where('cruiseDate', $bookingDateTime);
        //$book->where('cruiseID', $cruiseID);
        $book->where('cruiseID', $fulldayBookingId, '!=');
        $sameResults = $book->get('wpuq_santorini_new_bookings');

        if (!empty($sameResults)) : // if the user need a partial
            foreach ($sameResults as $sr) {
                if ($sr['cruiseID'] == $cruiseID || $cruiseID == $fulldayBookingId) {
                    unset($bookingShips[$sr['boat_number'] - 1]);
                }
            }
        endif;

        /**
         * Εδω γίνεται το τελικό booking.
         * Η εγγραφή καταγράφεται στη βάση.
         */

        if (!empty($bookingShips)) :
            $available_boat = array_rand($bookingShips); // returns the key.

            //        $data = array(
            //            'order_id' => 554454,
            //            'cruiseID' => $cruiseID,
            //            'personEmail' => 'amertzanos@seogreece.gr',
            //            'cruiseDate' => '2023-01-10 00:00:00',
            //            //'cruiseDate' => $bookingDateTime,
            //            'boat_number' => $bookingShips[$available_boat]
            //        );

            //$res = $book->insert('wpuq_santorini_new_bookings', $data);

            $alreadyBooked = false;
            $bookingMessage = 'You booked the <b>' . $cruiseID . '</b> on <span style="color:green">' . $bookingDateTime . '</span> <span style="color:red">' . $bookingShips[$available_boat] . '</span>';
        else :
            $bookingMessage = 'This cruise is not available this day.';
            $alreadyBooked = true;
            // Alternatives
            $altDays = array();
            for ($i = 1; $i <= 4; $i++) {


                $alts = show_alternatives(date('Y-m-d H:i:s', strtotime($bookingDateTime . '+' . $i . ' days')), $cruiseID);
                if (!empty($alts)) {
                    $altDays[] = date('Y-m-d', strtotime($bookingDateTime . '+' . $i . ' days'));
                }
                //print_r($alts);

            }
            $altMessage = 'This cruise is not available however you can book this cruise on the following days:<br/>' . implode(",\n", $altDays);

        endif;
    }

    $bookingInfo['boatsInfoMessage_1'] = 'Τα διαθέσιμα πλοία ειναι ' . implode(',', $bookingShips);
    $bookingInfo['boatsInfoMessage_2'] = 'Τα διαθέσιμα πλοία ειναι ' . implode(',', $bookingShips);
    $bookingInfo['futureCruises'] = $altMessage;
    $bookingInfo['message'] = $bookingMessage;

    // return $bookingInfo;

    echo json_encode(array(
        'rawdate' => $date,
        'date' => $bookingDateTime,
        'cruiseID' => $cruiseID,
        'discount' => $dis,
        'boat' => $bookingShips[$available_boat],
        'alreadyBooked' => $alreadyBooked,
        'alternativeMSG' => $bookingInfo['futureCruises'],
        'cruises_group' => $blockDates,
        'bookingMessage' => $bookingMessage,
        'success' => 1
    ));
    die();
}

add_action('wp_ajax_santorini_last_booking', 'santorini_last_booking');
add_action('wp_ajax_nopriv_santorini_last_booking', 'santorini_last_booking');

function show_alternatives($bookingDateTime, $cruiseToFind)
{
    $bookingShips = array('1', '2', '3', '4', '5');
    $fulldayBookingId = 1558;
    $bookingInfo = array();
    $book = travel_connect_to_db(); //connect..
    /**
     * 1. Εχουμε 4 πλοία. Βρίσκουμε ποιο/ποια ειναι fully booked τη συγκεκριμένη μέρα. Για του λογου το αληθές
     * το Full Day Cruise ειναι το 1558.
     */
    $book->where('cruiseDate', $bookingDateTime);
    $book->where('cruiseID', $fulldayBookingId);
    $results = $book->get('wpuq_santorini_new_bookings');

    if (!empty($results)) {
        foreach ($results as $r) {
            //            echo $r['boat_number'] . ' is fully booked for this day.';
            unset($bookingShips[$r['boat_number'] - 1]);
        }
    }

    $book->where('cruiseDate', $bookingDateTime);
    //$book->where('cruiseID', $cruiseID);
    $book->where('cruiseID', $fulldayBookingId, '!=');
    $sameResults = $book->get('wpuq_santorini_new_bookings');

    if (!empty($sameResults)) : // if the user need a partial
        foreach ($sameResults as $sr) {
            if ($sr['cruiseID'] == $cruiseToFind || $cruiseToFind == $fulldayBookingId) {
                echo $sr['boat_number'];
                unset($bookingShips[$sr['boat_number'] - 1]);
            }
        }
    endif;

    return $bookingShips;
}


function new_booking_cruise_weights($cruiseID)
{
    if ($cruiseID == 1558) {
        $weight = 1;
    } elseif ($cruiseID == 1555) {
        $weight = 0.5;
    } else if ($cruiseID == 1552) {

        $weight = 0.5;
    }
    return $weight;
}

add_action('admin_menu', 'rudr_top_lvl_menu');

function rudr_top_lvl_menu()
{

    add_menu_page(
        'New Bookings', // page <title>Title</title>
        'New Bookings', // link text
        'manage_options', // user capabilities
        'new-bookings', // page slug
        'new_bookings_callback', // this function prints the page content
        'dashicons-images-alt2', // icon (from Dashicons for example)
        100 // menu position
    );
}

function new_bookings_callback()
{

    echo '<div class="santorini-bookings" style="margin: 0 auto; padding: 15px;">';
    echo '<h3>View All Bookings</h3>';
    echo '<div id="calendar"></div>';
    echo '</div>';
}

//$screen = get_current_screen();
//print_r($screen);
function wpdocs_selectively_enqueue_admin_script($hook)
{
    global $pagenow;
    $current_screen = get_current_screen();

    if (strpos($current_screen->base, 'new-bookings') === false) {
        return;
    }
    wp_enqueue_style('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css', array(), '1.0', 'all');
    wp_enqueue_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js', array(), '1.0', true);
    wp_enqueue_script('fullcalendar-loc', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js', array(), '1.0', 'all');
    wp_enqueue_script('travelCal', get_stylesheet_directory_uri() . '/travel/assets/travel-cal.js', array(), time(), true);
    wp_localize_script('travelCal', 'bookingOBJ', array(
        'bookingDates' => get_booking_dates()
    ));
}

add_action('admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script');

function get_booking_dates()
{
    $travelDB = travel_connect_to_db();
    $res = $travelDB->get('wpuq_santorini_new_bookings');
    $orderArray = array();

    if (!empty($res)) :
        foreach ($res as $re) :
            $item = array(
                'title' => $re['order_id'] . '-' . $re['cruiseID'] . ' ' . $re['personEmail'],
                'start' => $re['cruiseDate'],
                'url' => 'https://santoriniboatrental.gr/wp-admin/post.php?post=' . $re['order_id'] . '&action=edit'
            );
            $orderArray[] = $item;
        endforeach;
    endif;
    $b = array(
        array('title' => 'Archimidis #9049- Full Day Booking', 'start' => '2022-12-26'),
        array('title' => 'George Half Day #9095', 'start' => '2022-12-25'),
    );
    //    return $b;

    return $orderArray;
}
