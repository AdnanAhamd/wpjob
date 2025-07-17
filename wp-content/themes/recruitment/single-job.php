<?php
/*
Template Name: Employers Template
*/

get_header();

if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}

$country = get_field('country');
$city = get_field('city');

$terms = get_the_terms(get_the_ID(), 'job-category');
$categories = '';
if ($terms && !is_wp_error($terms)) {
    $category_names = wp_list_pluck($terms, 'name');
    $categories = implode(', ', $category_names);
}
?>

<div class="gulf-container singleJobMainWrapper">
    <div class="gulf-title">Job Detail</div>
    <div class="singleJobWrapper">
    	<div class="jobItem">Job Title: <p><?php the_title(); ?></p></div>
		<div class="jobItem">Category: <p><?php echo esc_html($categories); ?></p></div>
		<div class="jobItem">Country: <p><?php echo esc_html($country); ?></p></div>
		<div class="jobItem">City: <p><?php echo esc_html($city); ?></p></div>
		<div class="jobItem">Date of Publish: <p><?php echo get_the_date(); ?></p></div>
		<div class="jobItem">Description: <p><?php the_content(); ?></p></div>

    	<div class="openText">
    		<p>OPEN</p>
    	</div>
    	<div class="applyLink">
    		<a href="<?php 	site_url(); ?>/recruitment/upload-your-cv/">APPLY</a>
    	</div>

    </div>

</div>


<?php

get_footer();