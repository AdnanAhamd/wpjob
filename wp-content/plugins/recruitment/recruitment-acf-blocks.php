<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/sofyansitorus
 * @since             1.0.0
 * @package           ALDS
 *
 * @wordpress-plugin
 * Plugin Name:       recruitment-blocks ACF Blocks

 * Description:       recruitment-blocks ACF Blocks.
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       recruitment
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.7.0
*/
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( !function_exists('get_field') ) {
    die;
}

if ( ! function_exists( 'get_plugin_data' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}


function home_banner(){
    if(function_exists('get_field')){
        $left_image=get_sub_field('left_image');
        $slider_images=get_sub_field('slider_images'); 
        $right_image=get_sub_field('right_image');
    }
    $html = '<div class="main-banner">
                <marquee onmouseover="this.stop();" onmouseout="this.start();">                &nbsp;&nbsp;<img src="'.site_url().'/wp-content/uploads/2025/06/marquee_image.png" alt="&quot;">&nbsp;&nbsp;.<a href="details_user.aspx?id=391&amp;Golf=True">مطلوب للعمل فني سيارات كهرباء وميكانيك  للامارات </a>&nbsp;&nbsp;</marquee>
                <div class="inner-main-banner">
                  <img class="inner-main-banner-img" src="'.$left_image.'" alt="">
                  <div class="job-banner">
                    <div class="slideshow-container">';
                    foreach ($slider_images as $image) {
    $html .='         <div class="mySlides fade">
                        <img src="'.$image['image'].'" style="width: 400px">
                      </div>';
                  }
    $html .='
                      <a class="prev" onclick="plusSlides(-1)">
                        <img src="'.site_url().'/wp-content/uploads/2025/06/left.gif" alt="Previous" />
                      </a>
                      <a class="next" onclick="plusSlides(1)">
                        <img src="'.site_url().'/wp-content/uploads/2025/06/right.gif" alt="Next" />
                      </a>
                    </div>

                    <br>


                  </div>
                  <img class="inner-main-banner-img" src="'.$right_image.'" alt="">
                </div>
              </div>';
return $html;
}

function home_content(){
    if(function_exists('get_field')){
        $heading=get_sub_field('heading');
        $image_1=get_sub_field('image_1'); 
        $image_2=get_sub_field('image_2');
    }
    $categories = get_terms(array(
        'taxonomy' => 'job-category',
        'hide_empty' => false,
    ));
    $html = '<div class="job-category-content">
            <p class="green-text">' . $heading . '</p>
            <div class="job-buttons homeCategoryButton">';

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $url = site_url('/latest-jobs-in-gulf/?page-name=latest-jobs-in-gulf&cat-id=' . $category->term_id);
            $html .= '<a href="' . esc_url($url) . '"><button>' . esc_html($category->name) . '</button></a>';
        }
    }

$html .= '</div>
            <div class="job-ads">
              <img src="' . $image_1 . '" alt="Job Apply">
              <img src="' . $image_2 . '" alt="Employers">
            </div>
          </div>';
return $html;
}

function about_us(){
    if(function_exists('get_field')){
        $image=get_sub_field('image');
        $logo=get_sub_field('logo'); 
        $mobile_number=get_sub_field('mobile_number');
        $phone_number=get_sub_field('phone_number');
        $email=get_sub_field('email');
        $website_url=get_sub_field('website_url');
    }
    $html = '<div class="about-us-section">
              <div class="about-image">
                <img src="'.$image.'" alt="About Us">
              </div>

              <div class="about-details">
                <img src="'.$logo.'" alt="Company Logo">
                <span class="highlight">Mobile: '.$mobile_number.'</span>
                <span class="highlight">Phone: '.$phone_number.'</span>

                <a href="mailto:'.$email.'">'.$email.'</a>
                <a href="'.$website_url.'" target="_blank">'.$website_url.'</a>
              </div>
            </div>';
return $html;
}

function func_omegatech_acf_blocks( $content ) {
    $shorthtml="";
    if( have_rows('content') ): 
        while ( have_rows('content') ) : the_row();
            if(get_row_layout() == "home_banner"):
                $shorthtml .=home_banner();
            elseif(get_row_layout() == "home_content"):
                $shorthtml .=home_content(); 
            elseif(get_row_layout() == "about_us"):
                $shorthtml .=about_us();  
            endif;     
        endwhile; else : endif; 
        return $shorthtml.$content;

    }
    add_filter( 'the_content', 'func_omegatech_acf_blocks' );



    function load_omega_acf_plugin() {
        require plugin_dir_path( __FILE__ ) . 'inc/core_functions.php';
    }
    add_action( 'init', 'load_omega_acf_plugin' );

    ?>

