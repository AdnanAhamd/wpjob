<?php
/*
Template Name: dashboard latest job Gulf 2
*/

// ✅ Start session if not already started
if (!session_id()) {
    session_start();
}

// ✅ Check session value
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['enter_system']) ||
    $_SESSION['enter_system'] !== 'entering_system'
) {
    // 🔁 Redirect to login page (change slug if needed)
    wp_redirect(home_url('/entering-system')); 
    exit;   
}
if(function_exists('get_field')){
    $breadcrumb = get_field('breadcrumb');
}
get_header('dashboard'); // Loads header-dashboard.php



$current_slug = get_post_field('post_name', get_post());

// Get region from ?page-name=
$region_slug = 'gulf'; // default
if (isset($_GET['page-name'])) {
    $page_slug = sanitize_text_field($_GET['page-name']);
    if ($page_slug === 'latest-jobs-in-gulf-2') {
        $region_slug = 'jordan';
    } elseif ($page_slug === 'latest-jobs-in-gulf-2') {
        $region_slug = 'gulf';
    }
}
else{
	if ($current_slug === 'latest-jobs-in-gulf-2') {
        $region_slug = 'jordan';
    } elseif ($current_slug === 'latest-jobs-in-gulf-2') {
        $region_slug = 'gulf';
    }
}

// Get selected category
$selected_cat_id = isset($_GET['cat-id']) ? intval($_GET['cat-id']) : 0;
if(!empty($breadcrumb)){
?>
<div class="page-banner">  
    <h3><?php echo esc_html($breadcrumb); ?></h3>
</div>
<?php } ?>
<div class="categoryWrapper">
    <p>Categories:</p>
    <div class="gulf-filters">
        <!-- ALL Button -->
        <a href="<?php echo esc_url(add_query_arg('page-name', $current_slug, site_url().'/'.$current_slug)); ?>">
            <button>ALL</button>
        </a>

        <?php
        $terms = get_terms(array(
            'taxonomy'   => 'job-category',
            'hide_empty' => false,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $url = add_query_arg(array(
                    'page-name' => $current_slug,
                    'cat-id'    => $term->term_id,
                ), site_url().'/'.$current_slug);

                $active_class = ($selected_cat_id === $term->term_id) ? 'style="background:#ffa500;color:#fff;"' : '';

                echo '<a href="' . esc_url($url) . '"><button ' . $active_class . '>' . esc_html($term->name) . '</button></a>';
            }
        }
        ?>
    </div>
</div>

<div class="gulf-job-section">
    <h3>Latest Jobs for region: <?php echo ucfirst(esc_html($region_slug)); ?></h3>

    <?php
    // Tax query for region
    $tax_query = array(
        array(
            'taxonomy' => 'region',
            'field'    => 'slug',
            'terms'    => $region_slug,
        ),
    );

    // If job category is selected, add to tax_query
    if ($selected_cat_id) {
        $tax_query[] = array(
            'taxonomy' => 'job-category',
            'field'    => 'term_id',
            'terms'    => $selected_cat_id,
        );
    }

    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => -1,
        'tax_query'      => $tax_query,
    );

    $jobs = new WP_Query($args);

    if ($jobs->have_posts()) : ?>
        <table class="gulf-table">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Title</th>
                    <th>Category</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Views</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($jobs->have_posts()) : $jobs->the_post(); 
                    if (get_post_meta(get_the_ID(), 'post_views_count', true) == '') {
                        update_post_meta(get_the_ID(), 'post_views_count', 0);
                    }
                    ?>
                    <tr>
                        <td><?php the_ID(); ?></td>
                        <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
                        <td>
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'job-category');
                            if ($terms && !is_wp_error($terms)) {
                                echo esc_html($terms[0]->name);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?php the_field('country'); ?></td>
                        <td><?php the_field('city'); ?></td>
                        <td><?php echo get_post_meta(get_the_ID(), 'post_views_count', true) ?: '0'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No jobs found.</p>
    <?php endif;

    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>
