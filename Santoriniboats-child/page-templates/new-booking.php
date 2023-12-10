<?php
/*
 * Template Name: New Booking Tests
 */
echo 'This is a test page.Please ignore';
echo '<hr/>';
$opts = get_option('santorin_options');
$discounts = $opts['discount-group'];
$discountArray = array();
$hasDiscount = false;
$discountVal = 1;
$testDate = '2023/01/31 00:00:00';
if (!empty($discounts)):
    foreach ($discounts as $d) {

        if ($d['cruise-select'] == 1558) {
           $discountVal = $d['cruise-discount-number'];
           $discountDays = $d['cruise-discount-dates'];

               $from = date_create($discountDays['from']);
               $bookingFrom = date_format($from, 'Y/m/d 00:00:00');

               $to = date_create($discountDays['to']);
               $bookingTo = date_format($to, 'Y/m/d 00:00:00');

               if(strtotime($testDate) >= strtotime($bookingFrom) && strtotime($testDate) <= strtotime($bookingTo)){
                   echo 'is between';
                   echo $dis = $discountVal;
                   break;
               }else{
                   echo 'No discount mate';
                   $dis = 1;
               }


//               echo $bookingFrom;
//               echo $bookingTo;
        }
    }
endif;
//$book = new_booking_validate_booking('2023-01-10 00:00:00',1558);
//$bookObj = santorini_last_booking('2023-01-10 00:00:00',1558);
//
//echo '<pre>';
//print_r($bookObj);
//echo '</pre>';
//

