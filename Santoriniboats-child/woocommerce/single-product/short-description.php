<?php
$post_id = get_the_ID();
$start_date = get_field('start_date', $post_id);
$end_date = get_field('end_date', $post_id);
$currentDateTime = date("Y-m-d H:i:s");

// echo $start_date . '</br>';
// echo $end_date . '</br>';
// echo $currentDateTime;
