<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Recruitment
 */
if(function_exists('get_field')){
	$breadcrumb=get_field('breadcrumb');
}

get_header();
?>
<div class="page-banner">  
        <h3><?php echo $breadcrumb; ?></h3>
      </div>
<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile; // End of the loop.
	?>

<?php
/*
get_sidebar();*/
get_footer();
