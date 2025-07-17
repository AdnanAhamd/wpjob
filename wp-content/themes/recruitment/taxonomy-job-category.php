<?php get_header(); 

if ( isset($_GET['from_page']) ) {
    $from_page_slug = sanitize_text_field($_GET['from_page']);
}
?>

<div class="page-banner">  
   <h3><?php single_term_title(); ?></h3>
</div>

<div class="categoryWrapper">
    <p>Categories:</p>
    <div class="gulf-filters">
      <a href="<?php echo site_url(); ?>/<?php echo $current_slug ; ?>"><button>ALL</button></a>
      <?php
        
        $terms = get_terms(array(
            'taxonomy'   => 'job-category',
            'hide_empty' => false,
        ));
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $term_link = get_term_link($term);
                if (!is_wp_error($term_link)) {
        echo '<a href="'.site_url().'/'.$from_page_slug . '"><button>' . esc_html($term->name) . '</button></a>';
                }
            }
        }
        ?>
    </div>
</div>

<?php if (have_posts()) : ?>
    <ul>
        <?php while (have_posts()) : the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else : ?>
    <p>No jobs found in this category.</p>
<?php endif; ?>

<?php get_footer(); ?>
