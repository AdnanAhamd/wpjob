<?php
/*
Template Name: admin-dashboard
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

get_header('dashboard'); // Loads header-dashboard.php
?>

<div class="dashboard">
  <h2 class="dashboard-title">Dashboard</h2>

  <!-- Employee Order Request Section -->
  <div class="section">
    <h3 class="section-title">Employee Order Request :-</h3>
      <table class="data-table">
      <thead>
        <tr>
          <th>Sr.</th>
          <th>Request Date</th>
          <th>Employer Name</th>
          <th>Contact No.</th>
          <th>Job Title.</th>
          <th>Nationality</th>
          <th>Qualification</th>
          <th>Specialization</th>
          <th>Experience</th>
        </tr>
      </thead>
      <tbody>
       <?php
global $wpdb;

$results = $wpdb->get_results("
  SELECT 
    meta.id,
    meta.date,
    emp.employer_name,
    meta.title,
    meta.marital_status,
    meta.required_number,
    meta.nationality,
    meta.qualification,
    meta.specialization,
    meta.experience
  FROM wp_employers_meta meta
  LEFT JOIN wp_employers emp ON meta.user_id = emp.id
  ORDER BY meta.id DESC
");

if (!empty($results)) {
  $sr = 1;
  foreach ($results as $row) {
    echo '<tr>';
    echo '<td>' . esc_html($sr++) . '</td>';
    echo '<td>' . esc_html(date('Y-m-d', strtotime($row->date))) . '</td>';
    echo '<td>' . esc_html($row->employer_name ?? 'N/A') . '</td>'; // company_name from wp_employers
    echo '<td>' . esc_html($row->marital_status) . '</td>';
    echo '<td>' . esc_html($row->title) . '</td>';
    echo '<td>' . esc_html($row->nationality) . '</td>';
    echo '<td>' . esc_html($row->qualification) . '</td>';
    echo '<td>' . esc_html($row->specialization) . '</td>';
    echo '<td>' . esc_html($row->experience) . '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="9">No job requests found.</td></tr>';
}
?>

      </tbody>
    </table>
  </div>

  <!-- New Job Openings Section (static for now) -->
   <div class="gulf-job-section">
    <h3>New Job Openings :-</h3>

    <?php
    // Tax query for region
    // $tax_query = array(
    //     array(
    //         'taxonomy' => 'region',
    //         'field'    => 'slug',
    //         'terms'    => $region_slug,
    //     ),
    // );

    // // If job category is selected, add to tax_query
    // if ($selected_cat_id) {
    //     $tax_query[] = array(
    //         'taxonomy' => 'job-category',
    //         'field'    => 'term_id',
    //         'terms'    => $selected_cat_id,
    //     );
    // }

    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => -1,
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
</div>

<?php get_footer(); ?>