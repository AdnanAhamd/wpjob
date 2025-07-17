<?php
/**
 * The header-dashboard for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package recruitment
 */

if(function_exists('get_field')){
	$social_icons=get_field('social_icons','options');
	$image_1=get_field('image_1','options');
	$image_2=get_field('image_2','options');
	$image_3=get_field('image_3','options');
	$image_4=get_field('image_4','options');
	$logo=get_field('logo','options');
	$site_icon=get_field('site_icon','options');

	if(empty($site_icon)){
		$site_icon= site_url().'/wp-content/uploads/2025/06/marquee_image.png';
	}
    if(empty($logo)){
		$logo= site_url().'/wp-content/uploads/2025/06/about_logo.gif';
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css">
    <title><?php bloginfo('name'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo $site_icon; ?>">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="container">
        <header class="top-bar">
            <div class="section-one-header">
                <div class="social-icons">
                	<?php 
                		foreach ($social_icons as $social_icon) {
                	 ?>
                    <a href="<?php echo $social_icon['url']; ?>"><img src="<?php echo $social_icon['icon']; ?>" alt="social icons"></a>
   				<?php } ?>
                </div>
                <img class="social-icons-img" src="<?php echo $image_1; ?>" alt="new">
            </div>

            <div class="logo-bar">
                <div class="logo-text">
                	<?php 
                	if(empty($logo)){
    					$logo=get_site_url().'/wp-content/uploads/2025/06/about_logo.gif" alt="site logo';
    				}?>
    				<a href="<?php echo site_url(); ?>"><img src="<?php echo $logo; ?>" alt="logo"></a>
                    
                    <img src="<?php echo $image_2; ?>" alt="image 1">
                </div>
            </div>
            <div class="section-two-header">
            	<?php echo do_shortcode('[gtranslate]'); ?>
                <img src="<?php echo $image_3; ?>" alt="image 1">
                <img src="<?php echo $image_4; ?>" alt="image 2">
            </div>
        </header>
        <?php recruitment_menu("menu","admin-menu"); ?>

